<?php
class pb2bFrontendApiBuyerDocflowTemplateDeleteController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        $template_item_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        $service = new pb2bDocflowTemplateService();
        $service->deleteItem($template_item_id, 1, $this->context->company()->id);

//        $template_item = (new pb2bDocflowTemplateItemsModel)->getById($template_item_id);
//        $this->response = $this->context->company()->docflowTemplateItemDeleteById($template_item['template_id'] ?? 0, $template_item_id);
    }
}