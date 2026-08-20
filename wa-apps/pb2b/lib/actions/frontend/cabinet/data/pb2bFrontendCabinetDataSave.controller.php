<?php
class pb2bFrontendCabinetDataSaveController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $data = waRequest::post('company', [], waRequest::TYPE_ARRAY);
        $company_info = array_merge($this->context->company()['data'], $data);
        $this->response = $this->context->company()->save($company_info);
    }
}