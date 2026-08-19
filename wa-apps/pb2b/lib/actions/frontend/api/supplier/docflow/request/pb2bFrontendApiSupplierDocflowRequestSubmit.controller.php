<?php
class pb2bFrontendApiSupplierDocflowRequestSubmitController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $request_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        $service = new pb2bDocflowRequestService();
        $request = $service->submitFromProvider($request_id, $this->context->company()->id);

        $this->response = ['error' => false, 'message' => "Заявка \"{$request->data['procedure_code']}\" успешно отправлена"];
    }
}