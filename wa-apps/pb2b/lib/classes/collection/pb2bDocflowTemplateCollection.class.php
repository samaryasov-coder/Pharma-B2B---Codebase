<?php

class pb2bDocflowTemplateCollection extends pb2bWaproCollection
{
    protected pb2bDocflowTemplateItemsModel $docflowTemplateItemsModel;
    protected pb2bFileLinksModel $fileLinksModel;
    protected pb2bFileModel $fileModel;

    public function __construct($hash = null)
    {
        $this->docflowTemplateItemsModel = new pb2bDocflowTemplateItemsModel();
        $this->fileLinksModel = new pb2bFileLinksModel();
        $this->fileModel = new pb2bFileModel();
        parent::__construct($hash);
    }

    protected function addWhereItems($where): void
    {
        $this->model->setJoin(array(
            array(
                'right' => $this->docflowTemplateItemsModel,
                'on'    => array('id' => 'template_id'),
                'type'  => 'LEFT',
                'as'    => 'DTI',
            ),
        ));
        $this->model->addWhere($where);
    }

    protected function addWhereItemsWithFiles($where): void
    {
        $this->model->setJoin(array(
            array(
                'right' => $this->docflowTemplateItemsModel,
                'on' => array('id' => 'template_id'),
                'type' => 'LEFT',
                'as' => 'DTI',
            ),
            array(
                'right' => $this->fileLinksModel,
                'on' => array(
                    'file_link_id' => array(
                        'table' => 'DTI',
                        'simile' => '=',
                        'value' => array('table' => 'FL', 'field' => 'id'),
                    ),
                ),
                'type' => 'LEFT',
                'as' => 'FL',
            ),
            array(
                'right' => $this->fileModel,
                'on' => array(
                    'file_id' => array(
                        'table' => 'FL',
                        'simile' => '=',
                        'value' => array('table' => 'F', 'field' => 'id'),
                    ),
                ),
                'type' => 'LEFT',
                'as' => 'F',
            ),
        ));
        $this->model->addWhere($where);
    }
}
