<?php

class pb2bDocflowTemplateDefaultGetController extends waJsonController
{
    public function execute(): void
    {
        $rows = pb2bDocflowTemplate::getDefaultDocuments();
        $items = array();

        foreach ($rows as $row) {
            $file_id = (int) ($row['file_id'] ?? 0);
            if ($file_id <= 0) continue;

            $items[] = array(
                'id' => $file_id,
                'file_id' => $file_id,
                'name' => (string) ($row['doc_name'] ?? ''),
                'type' => (string) ($row['company_type_name'] ?? 'Все типы'),
                'comment' => (string) ($row['doc_comment'] ?? ''),
                'file_name' => (string) ($row['original_filename'] ?? ''),
                'download_url' => '?module=files&action=download&file_id='.$file_id,
                'sort' => (int) ($row['sort'] ?? 0),
            );
        }

        $this->response = array(
            'error' => false,
            'items' => $items,
        );
    }
}
