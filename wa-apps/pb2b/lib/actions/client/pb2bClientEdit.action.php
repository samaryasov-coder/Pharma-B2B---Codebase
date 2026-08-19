<?php

class pb2bClientEditAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        $object = new pb2bClient(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->view->assign($object->get(array('includes' => array('tabs', 'contacts'))));
    }
}