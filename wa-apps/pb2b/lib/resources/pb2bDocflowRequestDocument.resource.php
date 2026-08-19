<?php
class pb2bDocflowRequestDocumentResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id'],
        'string' => ['template_name', 'comment'],
    ];

    public function toArray(): array
    {
        $file_link = new pb2bFileLink($this->item_provider_file_link_id ?? 0);
        if (!$file_link->id)
            $file_link = null;

        return [
            'id' => $this->item_id,
            'template_name' => $this->item_reviewer_name,
            'comment' => $this->item_provider_comment,
            'status' => pb2bDocflowRequestItemStatus::from($this->item_status)->toArray(),
            'file' => pb2bFileLinkResource::make($file_link)?->resolve()
        ];
    }
}