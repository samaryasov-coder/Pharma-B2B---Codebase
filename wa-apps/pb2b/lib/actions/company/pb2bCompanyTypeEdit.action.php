<?php

class pb2bCompanyTypeEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $this->assignObjectAndFields('company', waRequest::post('id', null, waRequest::TYPE_INT));
    }
}