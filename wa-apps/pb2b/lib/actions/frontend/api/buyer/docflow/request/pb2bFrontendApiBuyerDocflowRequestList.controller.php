<?php
class pb2bFrontendApiBuyerDocflowRequestListController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $service = new pb2bDocflowRequestService();
        $data = $service->getListByReviewer($this->context->company()->id);

        $this->response = pb2bDocflowRequestResource::collection($data);
    }
}