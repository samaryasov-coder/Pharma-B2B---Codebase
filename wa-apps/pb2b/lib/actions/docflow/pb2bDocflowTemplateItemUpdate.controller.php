<?php

class pb2bDocflowTemplateItemUpdateController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('company_id', null, waRequest::TYPE_INT));

        $template_item = waRequest::post('template_item', array(), waRequest::TYPE_ARRAY);
        $template_id = (int) waRequest::post('template_id', null, waRequest::TYPE_INT);
        $template_item_id = (int) waRequest::post('template_item_id', null, waRequest::TYPE_INT);
        if (!$template_item_id) {
            $template_item_id = (int) ($template_item['id'] ?? 0);
        }

        $this->response = $company->docflowTemplateItemUpdateFromUpload(
            $template_id,
            $template_item_id,
            $template_item,
            waRequest::post('input_name', 'file', waRequest::TYPE_STRING_TRIM)
        );
    }
}
