<?php

class pb2bCompanyAccreditationFilesAddAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        $object = new pb2bCompany(waRequest::get('id', null, waRequest::TYPE_INT));
        $this->view->assign(array(
            'object' => $object->data,
        ));
    }
}