<?php

class pb2bPrequalItemCollection extends pb2bWaproCollection
{
    public function getByPrequalId(int $prequal_id): array
    {
        if ($prequal_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('prequal_id' => array('value' => $prequal_id)));

        return $this->getCollection(array(
            'key' => false,
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
