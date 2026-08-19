<?php

class pb2bCompanySaveController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $company->save(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
    }
}