<?php

class pb2bCompanyDocflowRequestCreateController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('company_id', null, waRequest::TYPE_INT));
        $this->response = $company->docflowRequestCreate(waRequest::post('template_item', array(), waRequest::TYPE_ARRAY));
    }
}