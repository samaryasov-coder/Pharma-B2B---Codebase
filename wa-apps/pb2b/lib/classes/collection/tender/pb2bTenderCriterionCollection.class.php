<?php

class pb2bTenderCriterionCollection extends pb2bWaproCollection
{
    public function getByTenderId(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array();
        }

        $this->model->setWhere(array(
            'tender_id' => array('value' => $tender_id),
        ));

        return $this->getCollection(array(
            'key' => false,
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
