<?php
class pb2bFrontendApiBuyerDocflowRequestApproveController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $request_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        $service = new pb2bDocflowRequestService();
        $request = $service->approveFromReviewer($request_id, $this->context->company()->id);

        $this->response = ['error' => false, 'message' => "Заявка \"{$request->data['procedure_code']}\" утверждена"];
    }
}