<?php

class pb2bDocflowTemplateItemGetController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::request('company_id', null, waRequest::TYPE_INT));

        $template_id = (int) waRequest::request('template_id', null, waRequest::TYPE_INT);
        $template_item_id = (int) waRequest::request('template_item_id', null, waRequest::TYPE_INT);
        if (!$template_item_id) {
            $template_item_id = (int) waRequest::request('id', null, waRequest::TYPE_INT);
        }

        $this->response = $company->docflowTemplateItemGetById($template_id, $template_item_id);
    }
}
