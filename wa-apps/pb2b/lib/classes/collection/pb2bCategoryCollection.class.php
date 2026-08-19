<?php

class pb2bCategoryCollection extends pb2bWaproCollection
{
    protected function addWhereCompany($where) 
    {
        $model = new pb2bCompanyCategoryModel();
        $this->model->addJoin(array(
            array('right' => $model, 'on' => array('id' => 'category_id'))
        ));
        $this->model->addWhere($where);
    }
}