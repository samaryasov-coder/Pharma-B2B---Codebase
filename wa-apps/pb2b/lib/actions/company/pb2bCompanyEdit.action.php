<?php

class pb2bCompanyEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $object = new pb2bCompany(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->view->assign($object->get(array('includes' => array(
            'tabs', 
            'configFields', 
            'categories', 
            'docflowTemplateAccreditation',
        ))));
        $this->view->assign(array(
            'company_types' => pb2bWaproHelper::getConfigOption('company_type'),
            'types_organization' => pb2bWaproHelper::getConfigOption('type_organization'),
        ));
    }
}
