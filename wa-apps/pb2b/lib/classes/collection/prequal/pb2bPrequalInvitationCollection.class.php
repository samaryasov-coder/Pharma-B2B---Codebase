<?php

class pb2bPrequalInvitationCollection extends pb2bWaproCollection
{
    public function getByPrequalId(int $prequal_id): array
    {
        if ($prequal_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('prequal_id' => array('value' => $prequal_id)));
        $this->model->setJoin(array(array(
            'right' => new pb2bCompanyModel(),
            'on' => array('supplier_company_id' => 'id'),
            'type' => 'LEFT',
            'as' => 'C',
        )));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'prequal_id' => null,
                'supplier_company_id' => null,
                'invited_by_contact_id' => null,
                'status' => null,
                'sent_at' => null,
                'create_datetime' => null,
                'update_datetime' => null,
                array('field' => 'name', 'table' => 'C', 'as' => 'supplier_name'),
            ),
            'order' => array('id' => 'ASC'),
        ));
    }
}
