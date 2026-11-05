<?php

class pb2bPrequalApplicationItemCollection extends pb2bWaproCollection
{
    public function getByApplicationId(int $application_id): array
    {
        if ($application_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('application_id' => array('value' => $application_id)));
        $this->model->setJoin(array(
            array(
                'right' => new pb2bPrequalItemModel(),
                'on' => array('prequal_item_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'PI',
            ),
        ));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'application_id' => null,
                'prequal_item_id' => null,
                'decision' => null,
                'decision_comment' => null,
                'decided_by_contact_id' => null,
                'decided_at' => null,
                'valid_until' => null,
                'create_datetime' => null,
                'update_datetime' => null,
                array('field' => 'name', 'table' => 'PI', 'as' => 'item_name'),
                array('field' => 'qty', 'table' => 'PI', 'as' => 'qty'),
                array('field' => 'unit_id', 'table' => 'PI', 'as' => 'unit_id'),
            ),
            'order' => array('id' => 'ASC'),
        ));
    }
}
