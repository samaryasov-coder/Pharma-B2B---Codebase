<?php

class pb2bEsklpGroupCollection extends pb2bWaproCollection
{
    /**
     * @var pb2bEsklpGroupsModel
     */
    protected pb2bEsklpGroupsModel $groupsModel;

    public function __construct($hash = null)
    {
        $this->groupsModel = new pb2bEsklpGroupsModel();
        parent::__construct($hash);
    }

    protected function addWhereEsklp($where): void
    {
        $this->model->setJoin(array(
            array('right' => $this->groupsModel, 'on' => array('id' => 'group_id')),
        ));
        $this->model->addWhere($where);
    }
}