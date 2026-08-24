<?php
class pb2bFrontendApiBuyerDocflowRequestCancelController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $request_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        $service = new pb2bDocflowRequestService();
        $service->cancelFromReviewer($request_id, $this->context->company()->id);

        $this->response = [];
    }
}