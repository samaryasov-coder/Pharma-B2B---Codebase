<?php
class pb2bFrontendApiSupplierDocflowRequestListController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $company_id = $this->context->company()->id;

        $service = new pb2bDocflowRequestService();
        $data = $service->getListByProvider($company_id);

        $this->response = pb2bDocflowRequestResource::collection($data);
    }
}