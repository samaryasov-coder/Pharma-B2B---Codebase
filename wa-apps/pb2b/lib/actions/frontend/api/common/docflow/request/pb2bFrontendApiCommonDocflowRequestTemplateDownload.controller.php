<?php
class pb2bFrontendApiCommonDocflowRequestTemplateDownloadController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $request_item_id = waRequest::get('id', 0, waRequest::TYPE_INT);

        $service = new pb2bDocflowRequestService();
        $service->downloadItemReviewerFile($request_item_id, $this->context->company()->id);
    }
}