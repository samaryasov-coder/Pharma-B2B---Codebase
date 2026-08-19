<?php
class pb2bFrontendApiBuyerDocflowTemplateCreateController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $template_data = waRequest::post('template', [], waRequest::TYPE_ARRAY);
        $template_data['file'] = waRequest::file('file');

        $service = new pb2bDocflowTemplateService();
        $service->addItem(new pb2bDocflowTemplateItemDto($template_data), 1, $this->context->company()->id);
    }
}