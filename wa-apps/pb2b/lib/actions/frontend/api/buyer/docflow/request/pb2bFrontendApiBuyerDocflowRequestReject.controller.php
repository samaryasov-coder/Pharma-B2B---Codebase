<?php
class pb2bFrontendApiBuyerDocflowRequestRejectController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $request_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $company_id = $this->context->company()->id;


        $files = waRequest::post('files', [], waRequest::TYPE_ARRAY);
        $item_reasons = [];
        foreach ($files as $data) {
            $id = (int)($data['id'] ?? 0);
            $comment = trim($data['comment'] ?? '');

            if (!$id || $comment === '') {
                continue;
            }

            $item_reasons[] = [
                'id' => $id,
                'comment' => $comment
            ];
        }

        $service = new pb2bDocflowRequestService();
        $this->response = [];//$service->rejectFromReviewer($request_id, $company_id, $item_reasons);
    }
}