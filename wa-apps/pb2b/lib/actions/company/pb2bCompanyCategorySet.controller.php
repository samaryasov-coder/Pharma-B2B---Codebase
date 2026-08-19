<?php

class pb2bCompanyCategorySetController extends waJsonController
{
    public function execute(): void
    {
        $object = new pb2bCompany(waRequest::post('id', null, waRequest::TYPE_INT));
        $company_category_service = new pb2bCompanyCategoryService();

        $this->response = $company_category_service->setFirst(
            $object, 
            waRequest::post('ids', array(), waRequest::TYPE_ARRAY)
        );
    }
}