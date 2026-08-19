<?php
class pb2bFrontendCabinetRequestAction extends pb2bFrontendCabinetAction
{
    private function getRequestObject(){
        $request_id = waRequest::param('id', null, waRequest::TYPE_INT);
        $request = new pb2bDocflowRequest($request_id);
        return $request;
    }

    public function executeBuyer()
    {
        $request = $this->getRequestObject();
        $company = $request->getProviderCompany();

        $this->setCabinetThemeTemplate('request.html');
        $this->view->assign('request', $request);
        $this->view->assign('company_provider', $company);
    }

    public function executeSupplier()
    {
        $service = new pb2bDocflowRequestService();
        $request = $this->getRequestObject();
        $company = $request->getReviewerCompany();
        //$request_items = pb2bDocflowRequestTemplateWithDocumentResource::collection($service->getItemList($request->id));

        $this->setCabinetThemeTemplate('request.html');
        $this->view->assign('request', $request);
        $this->view->assign('company_reviewer', $company);
        $this->view->assign('request_items', $request->getItems());
    }
}