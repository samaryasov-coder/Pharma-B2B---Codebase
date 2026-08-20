<?php

class pb2bEsklpGroupEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $group = new pb2bEsklpGroup(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->view->assign($group->get(array('includes' => array('esklp', 'tabs'))));
    }
}