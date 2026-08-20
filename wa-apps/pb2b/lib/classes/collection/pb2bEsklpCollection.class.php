<?php

class pb2bEsklpCollection extends pb2bWaproCollection
{
    /**
     * @var pb2bEsklpGroupModel
     */
    protected pb2bEsklpGroupModel $groupModel;
    /**
     * @var pb2bEsklpGroupsModel
     */
    protected pb2bEsklpGroupsModel $groupsModel;

    public function __construct($hash = null)
    {
        $this->groupModel = new pb2bEsklpGroupModel();
        $this->groupsModel = new pb2bEsklpGroupsModel();
        parent::__construct($hash);
    }

    protected function addWhereGroup($where): void
    {
        $this->model->setJoin(array(
            array('right' => $this->groupsModel, 'on' => array('id' => 'esklp_id')),
        ));
        $this->model->addWhere($where);
    }
}