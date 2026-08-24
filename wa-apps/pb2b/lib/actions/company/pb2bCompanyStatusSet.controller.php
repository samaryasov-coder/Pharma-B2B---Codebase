<?php

class pb2bCompanyStatusSetController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $company->setStatus(waRequest::post('status', 0, waRequest::TYPE_INT));
    }
}
