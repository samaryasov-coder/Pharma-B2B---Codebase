<?php

class pb2bDocflowTemplateGetController extends waJsonController
{
    public function execute(): void
    {
        $template_id = waRequest::request('template_id', 0, waRequest::TYPE_INT);

        if (!$template_id) {
            $company_id = waRequest::request('company_id', 0, waRequest::TYPE_INT);
            $process_type = waRequest::request('process_type', 1, waRequest::TYPE_INT);
            if ($company_id > 0) {
                $template_model = new pb2bDocflowTemplateModel();
                $template_data = $template_model->getByField(array(
                    'company_id' => $company_id,
                    'process_type' => $process_type,
                ));
                $template_id = (int) ($template_data['id'] ?? 0);
            }
        }

        if (!$template_id) {
            $this->response = array('error' => false, 'template' => null, 'items' => array());
            return;
        }

        $docflow_template = new pb2bDocflowTemplate($template_id);
        if (!$docflow_template->id) {
            $this->response = array('error' => false, 'template' => null, 'items' => array());
            return;
        }

        $sort_by = strtolower((string) waRequest::request('sort_by', 'sort', waRequest::TYPE_STRING_TRIM));
        $sort_dir = strtolower((string) waRequest::request('sort_dir', 'asc', waRequest::TYPE_STRING_TRIM));
        $sort_map = array(
            'name' => 'name',
            'comment' => 'comment',
            'sort' => 'sort',
            'id' => 'id',
            'file_id' => 'file_id',
            'file' => 'file_id',
            'file_name' => 'file_name',
            'template_file' => 'file_name',
            'template_file_name' => 'file_name',
            'company_type_id' => 'company_type_id',
            'company_type' => 'company_type_id',
        );
        $sort_dir = $sort_dir === 'desc' ? 'desc' : 'asc';
        $sort_by = $sort_map[$sort_by] ?? 'sort';

        $source = strtolower((string) waRequest::request('source', 'default', waRequest::TYPE_STRING_TRIM));
        $get_params = array(
            'sort_by' => $sort_by,
            'sort_dir' => $sort_dir,
        );
        if ($source === 'join' || $source === 'with_files_join') {
            $items = $docflow_template->getItemsWithFiles($get_params);
        } else {
            $items = $docflow_template->getItems($get_params);
        }

        $this->response = array(
            'error' => false,
            'template' => array(
                'id' => (int) $docflow_template->id,
                'company_id' => (int) ($docflow_template->data['company_id'] ?? 0),
                'process_type' => (int) ($docflow_template->data['process_type'] ?? 0),
                'file_set_id' => (int) ($docflow_template->data['file_set_id'] ?? 0),
                'auto_request_enabled' => isset($docflow_template->data['auto_request_enabled'])
                    ? (int) $docflow_template->data['auto_request_enabled'] : 0,
                'refresh_period_days' => isset($docflow_template->data['refresh_period_days'])
                    ? (int) $docflow_template->data['refresh_period_days'] : null,
            ),
            'source' => $source,
            'items' => $items,
        );
    }
}
