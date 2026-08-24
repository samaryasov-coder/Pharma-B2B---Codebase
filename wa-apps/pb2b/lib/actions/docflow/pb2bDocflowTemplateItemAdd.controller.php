<?php

class pb2bDocflowTemplateItemAddController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('company_id', null, waRequest::TYPE_INT));
        $template_item = waRequest::post('template_item', array(), waRequest::TYPE_ARRAY);
        $process_type = waRequest::post('process_type', null, waRequest::TYPE_INT);
        if ($process_type !== null && !isset($template_item['process_type'])) {
            $template_item['process_type'] = $process_type;
        }

        $default_file_ids = waRequest::post('default_file_ids', null);
        if ($default_file_ids !== null && !isset($template_item['default_file_ids'])) {
            $template_item['default_file_ids'] = $default_file_ids;
        }

        $this->response = $company->docflowTemplateItemAddFromUpload($template_item);
    }
}
