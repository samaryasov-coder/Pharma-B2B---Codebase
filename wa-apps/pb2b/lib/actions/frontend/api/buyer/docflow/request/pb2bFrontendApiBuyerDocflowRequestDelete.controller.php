<?php
class pb2bFrontendApiBuyerDocflowRequestDeleteController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $request_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        $service = new pb2bDocflowRequestService();
        $service->deleteRequest($request_id);
    }
}