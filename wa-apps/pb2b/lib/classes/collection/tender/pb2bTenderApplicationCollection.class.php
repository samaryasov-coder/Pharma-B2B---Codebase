<?php

class pb2bTenderApplicationCollection extends pb2bWaproCollection
{
    public function __construct($hash = null)
    {
        $this->class_name = 'tender_application';
        $this->model = new pb2bTenderApplicationModel();
        parent::__construct($hash);
    }

    public function applicationCountsByTenderIds(array $tender_ids): array
    {
        $ids = array();
        foreach ($tender_ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $ids[$id] = $id;
            }
        }
        if (!$ids) return array();

        $this->model->setWhere(array(
            'tender_id' => array('simile' => 'IN', 'value' => array_values($ids)),
            'status' => array('simile' => 'IN', 'value' => array(
                pb2bTenderApplication::STATUS_DRAFT,
                pb2bTenderApplication::STATUS_SUBMITTED,
                pb2bTenderApplication::STATUS_WITHDRAWN,
            )),
        ));
        $this->model->setGroupBy(array('tender_id', 'status'));
        $rows = $this->getCollection(array(
            'key' => false,
            'select' => array(
                'tender_id' => null,
                'status' => null,
                array('func' => 'count', 'params' => array(array('field' => 'id')), 'as' => 'cnt'),
            ),
        ));

        $counts = array();
        foreach ($ids as $id) {
            $counts[$id] = array(
                pb2bTenderApplication::STATUS_DRAFT => 0,
                pb2bTenderApplication::STATUS_SUBMITTED => 0,
                pb2bTenderApplication::STATUS_WITHDRAWN => 0,
            );
        }
        foreach ($rows as $row) {
            $counts[(int) $row['tender_id']][$row['status']] = (int) $row['cnt'];
        }

        return $counts;
    }

    protected function includeCounts(array &$items, array $ids): void
    {
        $counts = array();
        foreach (array('items_count' => new pb2bTenderApplicationItemCollection(), 'documents_count' => new pb2bTenderApplicationDocumentCollection()) as $field => $collection) {
            $collection->model->setWhere(array(
                'application_id' => array('simile' => 'IN', 'value' => $ids),
            ));
            if ($field === 'documents_count') {
                $collection->model->addWhere(array('file_link_id' => array('simile' => '>', 'value' => array(0))));
            }
            $collection->model->setGroupBy(array('application_id'));
            $counts[$field] = $collection->getCollection(array(
                'key' => 'application_id',
                'select' => array(
                    'application_id' => null,
                    array('func' => 'count', 'params' => array(array('field' => 'id')), 'as' => 'cnt'),
                ),
            ));
        }
        foreach ($items as &$item) {
            foreach ($counts as $field => $rows) {
                $item[$field] = (int) ($rows[(int) $item['id']]['cnt'] ?? 0);
            }
        }
        unset($item);
    }

    protected function includeSupplier(array &$items, array $ids): void
    {
        $company_ids = array_unique(array_column($items, 'supplier_company_id'));
        $companies = (new pb2bCompanyCollection(array('col' => array(
            'id' => array('simile' => 'IN', 'value' => $company_ids),
        ))))->getCollection();
        foreach ($items as &$item) {
            $item['supplier'] = $companies[(int) $item['supplier_company_id']] ?? null;
        }
        unset($item);
    }
}
