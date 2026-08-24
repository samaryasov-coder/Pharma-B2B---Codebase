<?php
class pb2bFrontendCabinetAccreditationBuyerFormAction extends pb2bFrontendCabinetBuyerFormAction
{
    protected string $module = 'accreditation';

    public function requestCreateAction(){}

    public function templateCreateAction(){}

    public function templateEditAction()
    {
        $item_id = waRequest::get('id', 0, waRequest::TYPE_INT);

        $template_service = new pb2bDocflowTemplateService();
        $template_item = $template_service->getTemplateItem($item_id, $this->context->company()->id);
        $file = $template_item->getFileLink()?->getFile();

        $this->view->assign('template', $template_item);
        $this->view->assign('file', $file);

    }
}