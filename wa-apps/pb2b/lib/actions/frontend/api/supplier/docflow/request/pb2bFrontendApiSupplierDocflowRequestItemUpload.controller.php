<?php
class pb2bFrontendApiSupplierDocflowRequestItemUploadController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $request_item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $provider_comment = waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM);

        $service = new pb2bDocflowRequestService();
        $service->uploadItemFromProvider($request_item_id, waRequest::file('file'), $this->context->company()->id, $provider_comment);

        return $this->response = [];
    }
}