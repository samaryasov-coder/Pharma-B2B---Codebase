<?php

class pb2bTenderDocumentResource extends pb2bBaseJsonResource
{
    public function toArray(): array
    {
        $row = $this->resource;
        $file_link_id = (int) ($row['file_link_id'] ?? 0);
        return array(
            'id' => (int) ($row['id'] ?? 0),
            'kind' => (string) ($row['kind'] ?? ''),
            'name' => (string) ($row['name'] ?? ''),
            'description' => (string) ($row['description'] ?? ''),
            'is_required' => !empty($row['is_required']) ? 1 : 0,
            'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
            'file_name' => (string) ($row['file_name'] ?? ''),
            'sort' => (int) ($row['sort'] ?? 0),
        );
    }
}
