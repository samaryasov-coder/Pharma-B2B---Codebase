<?php

class pb2bPrequalApplicationHistoryCollection extends pb2bWaproCollection
{
    public function getByApplicationId(int $application_id): array
    {
        if ($application_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('application_id' => array('value' => $application_id)));

        return $this->getCollection(array(
            'key' => false,
            'order' => array('create_datetime' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
