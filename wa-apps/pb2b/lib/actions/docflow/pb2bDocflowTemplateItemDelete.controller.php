<?php

class pb2bDocflowTemplateItemDeleteController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('company_id', null, waRequest::TYPE_INT));
        $template_id = (int) waRequest::post('template_id', null, waRequest::TYPE_INT);
        $template_item_id = (int) waRequest::post('template_item_id', null, waRequest::TYPE_INT);
        if (!$template_item_id) {
            $template_item_id = (int) waRequest::post('id', null, waRequest::TYPE_INT);
        }

        $this->response = $company->docflowTemplateItemDeleteById($template_id, $template_item_id);
    }
}
