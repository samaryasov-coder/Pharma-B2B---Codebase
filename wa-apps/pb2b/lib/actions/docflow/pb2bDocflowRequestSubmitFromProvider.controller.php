<?php

class pb2bDocflowRequestSubmitFromProviderController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('provider_id', null, waRequest::TYPE_INT));
        if (!$company->id) {
            $this->response = array(
                'error' => true,
                'message' => 'Компания поставщика не найдена',
            );
            return;
        }
        $this->response = $company->docflowRequestSubmitFromProvider(
            waRequest::post('request_id', null, waRequest::TYPE_INT)
        );
    }
}
