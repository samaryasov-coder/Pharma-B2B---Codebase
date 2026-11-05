<?php

class pb2bTenderClassifierCollection extends pb2bWaproCollection
{
    public function __construct($hash = null)
    {
        $this->class_name = 'tender_classifier';
        $this->model = new pb2bTenderClassifierModel();
        parent::__construct($hash);
    }

    public function getByTenderId(int $tender_id): array
    {
        if ($tender_id <= 0) return array();

        $this->model->setWhere(array(
            'tender_id' => array('value' => $tender_id),
        ));
        $types = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'code');
        $table = $this->model->getTableName();
        $this->model->setJoin(array(
            array(
                'right' => new pb2bEsklpModel(),
                'as' => 'E',
                'type' => 'LEFT',
                'on' => array(
                    'classifier_id' => array('table' => $table, 'value' => array('table' => 'E', 'field' => 'id')),
                    'classifier_type' => array('table' => $table, 'value' => (int) $types['esklp']['id']),
                ),
            ),
            array(
                'right' => new pb2bCategoryModel(),
                'as' => 'C',
                'type' => 'LEFT',
                'on' => array(
                    'classifier_id' => array('table' => $table, 'value' => array('table' => 'C', 'field' => 'id')),
                    'classifier_type' => array('table' => $table, 'value' => (int) $types['category']['id']),
                ),
            ),
        ));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'tender_id' => null,
                'classifier_type' => null,
                'classifier_id' => null,
                array('field' => 'name', 'table' => 'E', 'as' => 'esklp_name'),
                array('field' => 'name', 'table' => 'C', 'as' => 'category_name'),
            ),
            'order' => array('classifier_type' => 'ASC', 'classifier_id' => 'ASC'),
        ));
    }
}
