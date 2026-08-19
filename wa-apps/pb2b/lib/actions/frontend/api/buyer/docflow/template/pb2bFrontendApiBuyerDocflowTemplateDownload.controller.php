<?php
class pb2bFrontendApiBuyerDocflowTemplateDownloadController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $template_item_id = waRequest::get('id', 0, waRequest::TYPE_INT);
        $service = new pb2bDocflowTemplateService();
        $service->downloadItemFile($template_item_id, 1, $this->context->company()->id);
    }
}