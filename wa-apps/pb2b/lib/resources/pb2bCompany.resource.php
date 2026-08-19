<?php
class pb2bCompanyResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id'],
        'string' => ['name', 'ext', 'link'],
    ];

    public function toArray(): array
    {
        return [
            'id' => $this->data['id'],
            'name' => $this->data['name'],
            'fullname' => $this->resource->getFullName(),
            'company_type' => $this->resource->getType()->toArray(),
            'type_organization' =>  $this->resource->getOrganizationType()?->toArray(),
        ];
    }
}