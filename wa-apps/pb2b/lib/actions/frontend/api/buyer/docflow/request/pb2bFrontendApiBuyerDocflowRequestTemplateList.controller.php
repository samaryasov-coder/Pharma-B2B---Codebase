<?php
class pb2bFrontendApiBuyerDocflowRequestTemplateListController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $request_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $company_id = $this->context->company()->id;

        $service = new pb2bDocflowRequestService();
        $data = $service->getItemList($request_id, $company_id);

        $this->response = pb2bDocflowRequestTemplateResource::collection($data);
    }
}