<?php
class pb2bDocflowRequestTemplateResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id'],
        'string' => ['template_name', 'comment'],
    ];

    public function toArray(): array
    {
        $file_link = new pb2bFileLink($this->item_reviewer_file_link_id ?? 0);

        return [
            'id' => $this->item_id,
            'template_name' => $this->item_reviewer_name,
            'comment' => $this->item_reviewer_comment,
            'file' => pb2bFileLinkResource::make($file_link)?->resolve()
        ];
    }
}