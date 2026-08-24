<?php
class pb2bFrontendCabinetRequestBuyerFormAction extends pb2bFrontendCabinetBuyerFormAction
{
    protected string $module = 'request';

    public function rejectAction()
    {
        $request_id = waRequest::post('request_id', 0, waRequest::TYPE_INT);
        $service = new pb2bDocflowRequestService();
        $data = $service->getItemList($request_id);
        $this->view->assign('template_list', pb2bDocflowRequestTemplateResource::collection($data));
    }
}