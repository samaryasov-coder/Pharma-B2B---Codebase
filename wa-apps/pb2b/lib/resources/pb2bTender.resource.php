<?php

class pb2bTenderResource extends pb2bBaseJsonResource
{
    protected array $casts = [
        'int' => ['id', 'tender_id'],
        'string' => [
            'number',
            'title',
            'create_datetime',
            'update_datetime',
            'start_at',
            'end_at',
            'opening_at',
            'published_at',
        ],
    ];

    public function toArray(): array
    {
        $row = is_array($this->resource)
            ? $this->resource
            : (array) ($this->data ?? []);

        $typeId = (int) ($row['type'] ?? 0);
        $statusId = (int) ($row['status'] ?? 0);
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $typeRow = is_array($types[$typeId] ?? null) ? $types[$typeId] : [];
        $statusRow = is_array($statuses[$statusId] ?? null) ? $statuses[$statusId] : [];

        $id = (int) ($row['id'] ?? 0);

        return [
            'id' => $id,
            'tender_id' => $id,
            'number' => (string) ($row['number'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),
            'type' => [
                'id' => $typeId,
                'code' => (string) ($typeRow['code'] ?? ''),
                'name' => (string) ($typeRow['name'] ?? ''),
            ],
            'status' => [
                'id' => $statusId,
                'code' => (string) ($statusRow['code'] ?? ''),
                'name' => (string) ($statusRow['name'] ?? ''),
            ],
            'is_private' => (int) ($row['is_private'] ?? 0),
            'approval_required' => (int) ($row['approval_required'] ?? 0),
            'retendering_enabled' => (int) ($row['retendering_enabled'] ?? 0),
            'itemized_enabled' => (int) ($row['itemized_enabled'] ?? 0),
            'start_at' => $row['start_at'] ?? null,
            'end_at' => $row['end_at'] ?? null,
            'opening_at' => $row['opening_at'] ?? null,
            'published_at' => $row['published_at'] ?? null,
            'budget' => $row['budget'] ?? null,
            'currency' => (string) ($row['currency'] ?? ''),
            'payment_terms' => (string) ($row['payment_terms'] ?? ''),
            'delivery_terms' => (string) ($row['delivery_terms'] ?? ''),
            'create_datetime' => $row['create_datetime'] ?? null,
            'update_datetime' => $row['update_datetime'] ?? null,
        ];
    }
}
