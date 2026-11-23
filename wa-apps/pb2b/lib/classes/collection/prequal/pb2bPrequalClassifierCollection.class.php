<?php

class pb2bPrequalClassifierCollection extends pb2bWaproCollection
{
    public function getByPrequalId(int $prequal_id): array
    {
        if ($prequal_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('prequal_id' => array('value' => $prequal_id)));
        $types = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'code');
        $table = $this->model->getTableName();
        $this->model->setJoin(array(array(
            'right' => new pb2bCategoryModel(),
            'as' => 'C',
            'type' => 'LEFT',
            'on' => array(
                'classifier_id' => array('table' => $table, 'value' => array('table' => 'C', 'field' => 'id')),
                'classifier_type' => array('table' => $table, 'value' => (int) $types['category']['id']),
                'row_kind' => array('table' => $table, 'value' => pb2bPrequalClassifier::CLASSIFIER),
            ),
        )));
        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null, 'prequal_id' => null, 'row_kind' => null, 'classifier_type' => null,
                'classifier_id' => null, 'tag_id' => null, 'sort' => null,
                array('field' => 'name', 'table' => 'C', 'as' => 'category_name'),
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
