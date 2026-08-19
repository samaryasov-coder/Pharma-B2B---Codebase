<?php

class pb2bDocflowRequestCreateFromProviderController extends waJsonController
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
        $this->response = $company->docflowRequestCreateFromProvider(array(
            'reviewer_id' => waRequest::post('reviewer_id', null, waRequest::TYPE_INT),
            'template_id' => waRequest::post('template_id', null, waRequest::TYPE_INT),
            'comment' => waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM),
        ));
    }
}
