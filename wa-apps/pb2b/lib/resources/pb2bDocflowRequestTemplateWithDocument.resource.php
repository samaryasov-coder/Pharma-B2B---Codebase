<?php
class pb2bDocflowRequestTemplateWithDocumentResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['item_id'],
        'string' => ['template_name', 'comment'],
    ];

    public function toArray(): array
    {
        $file_set = new waproFileSet($this->file_set_id ?? 0);

        return [
            'id' => $this->item_id,
            'status' => pb2bHelper::getStatusType($this->item_status),

            'template_name' => $this->item_reviewer_name,
            'template_comment' => $this->item_reviewer_comment,
            'template_file' => pb2bFileLinkResource::make($file_set->getFile($this->item_sample_file_id ?? 0))?->resolve(),

            'document_comment' => $this->item_provider_comment,
            'document_file' => pb2bFileLinkResource::make($file_set->getFile($this->item_provider_file_id ?? 0))?->resolve()
        ];
    }
}