<?php

class pb2bTenderInvitationCollection extends pb2bWaproCollection
{
    protected pb2bCompanyModel $companyModel;

    public function __construct($hash = null)
    {
        $this->companyModel = new pb2bCompanyModel();
        parent::__construct($hash);
    }

    public function existsForTenderAndCompany(int $tender_id, int $supplier_company_id): bool
    {
        if ($tender_id <= 0 || $supplier_company_id <= 0) {
            return false;
        }
        $this->model->setWhere(array(
            'tender_id' => array('value' => $tender_id),
            'supplier_company_id' => array('value' => $supplier_company_id),
        ));
        $rows = $this->getCollection(array(
            'key' => false,
            'select' => array('id' => null),
            'limit' => 1,
        ));

        return !empty($rows);
    }

    public function countsByTenderIds(array $tender_ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $tender_ids), static fn(int $id): bool => $id > 0)));
        if (!$ids) return array();

        $this->model->setWhere(array(
            'tender_id' => array('simile' => 'IN', 'value' => $ids),
        ));
        $this->model->setGroupBy(array('tender_id'));
        $rows = $this->getCollection(array(
            'key' => 'tender_id',
            'select' => array(
                'tender_id' => null,
                array('func' => 'count', 'params' => array(array('field' => 'id')), 'as' => 'cnt'),
            ),
        ));

        return array_map(static fn(array $row): int => (int) $row['cnt'], $rows);
    }

    public function getByTenderId(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array();
        }

        $this->model->setWhere(array(
            'tender_id' => array('value' => $tender_id),
        ));
        $this->model->setJoin(array(
            array(
                'right' => $this->companyModel,
                'on' => array('supplier_company_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'C',
            ),
        ));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'tender_id' => null,
                'supplier_company_id' => null,
                'invited_by_contact_id' => null,
                'status' => null,
                'sent_at' => null,
                'responded_at' => null,
                array('field' => 'id', 'table' => 'C', 'as' => 'company_id'),
                array('field' => 'name', 'table' => 'C', 'as' => 'company_name'),
                array('field' => 'company_type', 'table' => 'C', 'as' => 'company_type'),
                array('field' => 'type_organization', 'table' => 'C', 'as' => 'company_organization_type'),
            ),
            'order' => array('id' => 'ASC'),
        ));
    }
}
