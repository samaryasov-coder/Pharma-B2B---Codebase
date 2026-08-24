<?php
class pb2bFileLinkResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'string' => ['name', 'ext'],
        'int' => ['size'],
    ];

    public function toArray(): array
    {
        $file = $this->resource->getFile();

        return [
            'name' => $this->data['filename'],
            'ext' => $file?->data['ext'],
            'size' => $file?->data['size'],
        ];
    }
}