<?php

class pb2bDocflowTemplateItemPolicy
{
    public static function view(pb2bDocflowTemplateItem $template_item, pb2bCompany $company): bool
    {
        $template = $template_item->getTemplate();
        return pb2bDocflowTemplatePolicy::view($template, $company);
    }

    public static function update(pb2bDocflowTemplateItem $template_item, int $template_process_type, pb2bCompany $company): bool
    {
        $template = $template_item->getTemplate();
        return ((int)$template->data['process_type'] == $template_process_type) && ($template->getCompany()->id == $company->id);
    }

    public static function delete(pb2bDocflowTemplateItem $template_item, int $template_process_type, pb2bCompany $company): bool
    {
        $template = $template_item->getTemplate();
        return ((int)$template->data['process_type'] == $template_process_type) && ($template->getCompany()->id == $company->id);
    }

    public static function downloadFile(pb2bDocflowTemplateItem $template_item, int $template_process_type, pb2bCompany $company): bool
    {
        $template = $template_item->getTemplate();
        return ((int)$template->data['process_type'] == $template_process_type) && ($template->getCompany()->id == $company->id);
    }
}