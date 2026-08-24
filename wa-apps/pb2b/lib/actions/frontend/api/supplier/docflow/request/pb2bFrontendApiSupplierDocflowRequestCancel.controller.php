<?php
class pb2bFrontendApiSupplierDocflowRequestCancelController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $request_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $company_id = $this->context->company()->id;

        $service = new pb2bDocflowRequestService();
        $request = $service->revokeFromProvider($request_id, $company_id);

        $this->response = ['error' => false, 'message' => "Заявка \"{$request->data['procedure_code']}\" успешно отозвана"];
    }
}