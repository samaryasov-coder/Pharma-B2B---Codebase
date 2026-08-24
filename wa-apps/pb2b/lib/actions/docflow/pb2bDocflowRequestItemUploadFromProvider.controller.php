<?php

class pb2bDocflowRequestItemUploadFromProviderController extends waJsonController
{
    public function execute(): void
    {
        $docflowRequest = new pb2bDocflowRequest(waRequest::post('request_id', null, waRequest::TYPE_INT));
        if (!$docflowRequest->id) {
            $this->response = array(
                'error' => true,
                'message' => 'Процесс не найден',
            );
            return;
        }
        $this->response = $docflowRequest->uploadItemFromProvider(
            waRequest::post('provider_id', null, waRequest::TYPE_INT),
            waRequest::post('request_item_id', null, waRequest::TYPE_INT),
            waRequest::post('input_name', 'file', waRequest::TYPE_STRING_TRIM),
            waRequest::post('provider_comment', null, waRequest::TYPE_STRING_TRIM),
        );
    }
}
