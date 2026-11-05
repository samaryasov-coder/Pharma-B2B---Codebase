<?php

class pb2bTenderApplicationItemCollection extends pb2bWaproCollection
{
    public function __construct($hash = null)
    {
        $this->class_name = 'tender_application_item';
        $this->model = new pb2bTenderApplicationItemModel();
        parent::__construct($hash);
    }

    public function getByApplicationId(int $application_id): array
    {
        if ($application_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('application_id' => array('value' => $application_id)));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'application_id' => null,
                'tender_item_id' => null,
                'qty' => null,
                'unit' => null,
                'price_per_unit' => null,
                'vat_rate' => null,
                'is_winner' => null,
                'sort' => null,
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
