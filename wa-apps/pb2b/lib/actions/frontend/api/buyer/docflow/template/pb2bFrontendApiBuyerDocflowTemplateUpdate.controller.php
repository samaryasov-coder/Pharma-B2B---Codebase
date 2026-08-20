<?php
class pb2bFrontendApiBuyerDocflowTemplateUpdateController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $template_data = waRequest::post('template', [], waRequest::TYPE_ARRAY);
        $template_data['file'] = waRequest::file('file');
        $template_item_id = $template_data['item_id'] ?? 0;

        $service = new pb2bDocflowTemplateService();
        $service->updateItem($template_item_id, new pb2bDocflowTemplateItemDto($template_data), 1, $this->context->company()->id);
    }
}