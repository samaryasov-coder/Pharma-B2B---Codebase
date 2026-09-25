<?php

class pb2bTenderApplicationCriterionResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id', 'application_id', 'criterion_id', 'file_link_id', 'confirmed'],
        'string' => ['value'],
    ];

    public function toArray(): array
    {
        $row = pb2bTenderApplicationItemResource::waproRow($this->resource);
        $file_link_id = (int) ($row['file_link_id'] ?? 0);

        return array(
            'id' => (int) ($row['id'] ?? 0),
            'application_id' => (int) ($row['application_id'] ?? 0),
            'criterion_id' => (int) ($row['criterion_id'] ?? 0),
            'value' => $row['value'] ?? null,
            'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
            'confirmed' => !empty($row['confirmed']) ? 1 : 0,
        );
    }
}
