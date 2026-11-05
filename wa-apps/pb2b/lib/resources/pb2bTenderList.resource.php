<?php

class pb2bTenderListResource extends pb2bBaseJsonResource
{
    protected array $casts = array(
        'int' => array(
            'id',
            'type',
            'status',
            'is_private',
            'invited_count',
            'participants_count',
            'proposals_count',
            'withdrawn_count',
        ),
        'string' => array(
            'number',
            'title',
            'end_at',
            'create_datetime',
            'update_datetime',
        ),
    );

    public function toArray(): array
    {
        $row = $this->resource;
        $types = pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $statuses = pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $type = $types[$row['type']] ?? array();
        $status = $statuses[$row['status']] ?? array();

        return array(
            'id' => $row['id'],
            'number' => $row['number'],
            'title' => $row['title'],
            'type' => $row['type'],
            'status' => $row['status'],
            'is_private' => $row['is_private'],
            'end_at' => $row['end_at'],
            'create_datetime' => $row['create_datetime'],
            'update_datetime' => $row['update_datetime'],
            'type_code' => $type['code'] ?? '',
            'type_name' => $type['modal_name'] ?? $type['name'] ?? '',
            'status_code' => $status['code'] ?? '',
            'status_name' => $status['name'] ?? '',
            'status_tone' => $status['tone'] ?? 'info',
            'invited_count' => $row['invited_count'],
            'participants_count' => $row['participants_count'],
            'proposals_count' => $row['proposals_count'],
            'withdrawn_count' => $row['withdrawn_count'],
        );
    }
}
