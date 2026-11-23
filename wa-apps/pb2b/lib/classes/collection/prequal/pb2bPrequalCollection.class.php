<?php

class pb2bPrequalCollection extends pb2bWaproCollection
{
    public function getBuyerList(int $company_id): array
    {
        if ($company_id <= 0) return array();
        $this->model->setWhere(array('reviewer_id' => array('value' => $company_id)));
        return $this->getCollection(array(
            'key' => false,
            'select' => array('id' => null, 'procedure_code' => null, 'title' => null, 'status' => null, 'is_private' => null, 'accept_end_datetime' => null, 'create_datetime' => null, 'update_datetime' => null),
            'order' => array('update_datetime' => 'DESC', 'id' => 'DESC'),
        ));
    }
    public function getSupplierCandidates(): array
    {
        $this->model->setJoin(array(array(
            'right' => new pb2bCompanyModel(),
            'on' => array('reviewer_id' => 'id'),
            'type' => 'LEFT',
            'as' => 'C',
        )));
        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null, 'procedure_code' => null, 'title' => null, 'reviewer_id' => null,
                'status' => null, 'is_private' => null, 'accept_end_datetime' => null,
                'create_datetime' => null, 'update_datetime' => null,
                array('field' => 'name', 'table' => 'C', 'as' => 'organizer_name'),
            ),
            'order' => array('update_datetime' => 'DESC', 'id' => 'DESC'),
        ));
    }
}
