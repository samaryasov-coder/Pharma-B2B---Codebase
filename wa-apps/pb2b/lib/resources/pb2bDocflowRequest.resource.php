<?php
class pb2bDocflowRequestResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id'],
        'string' => ['procedure_code', 'create_datetime', 'expires_datetime'],
    ];

    public function toArray(): array
    {
        $reviewer_company = new pb2bCompany($this->reviewer_company_id ?? 0);
        $provider_company = new pb2bCompany($this->provider_company_id ?? 0);

        return [
            'id' => $this->id,
            'procedure_code' => $this->procedure_code,
            'create_datetime' => $this->create_datetime,
            'expires_datetime' => $this->expires_datetime,
            'status' => pb2bDocflowRequestStatus::from($this->status)->toArray(),
            'code' => null,

            'company_reviewer' => pb2bCompanyResource::make($reviewer_company)?->resolve(),
            'company_provider' => pb2bCompanyResource::make($provider_company)?->resolve(),
        ];
    }
}