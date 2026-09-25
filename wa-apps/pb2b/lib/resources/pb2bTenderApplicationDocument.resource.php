<?php

class pb2bTenderApplicationDocumentResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id', 'application_id', 'tender_document_id', 'file_link_id', 'sort'],
        'string' => ['name', 'comment'],
    ];

    public function toArray(): array
    {
        $row = pb2bTenderApplicationItemResource::waproRow($this->resource);
        $tender_document_id = (int) ($row['tender_document_id'] ?? 0);
        $file_link_id = (int) ($row['file_link_id'] ?? 0);

        return array(
            'id' => (int) ($row['id'] ?? 0),
            'application_id' => (int) ($row['application_id'] ?? 0),
            'tender_document_id' => $tender_document_id > 0 ? $tender_document_id : null,
            'name' => (string) ($row['name'] ?? ''),
            'comment' => $row['comment'] ?? null,
            'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
            'sort' => (int) ($row['sort'] ?? 0),
        );
    }
}
