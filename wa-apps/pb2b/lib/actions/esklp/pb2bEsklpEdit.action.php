<?php

class pb2bEsklpEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $esklp = new pb2bEsklp(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->view->assign($esklp->get(array('includes' => array('groups', 'tabs'))));
    }
}