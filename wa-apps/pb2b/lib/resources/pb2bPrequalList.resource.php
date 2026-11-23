<?php

class pb2bPrequalListResource extends pb2bBaseJsonResource
{
    public function toArray(): array
    {
        $row = $this->resource;
        $statuses = (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'id');
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'code');
        $status = $statuses[(int) $row['status']] ?? array();
        $type = $types[pb2bTenderType::PREQUALIFICATION->value] ?? array();
        return array(
            'id' => (int) $row['id'],
            'kind' => 'prequal',
            'number' => $row['procedure_code'],
            'title' => $row['title'],
            'type_code' => pb2bTenderType::PREQUALIFICATION->value,
            'type_name' => $type['modal_name'] ?? $type['name'] ?? '',
            'status_code' => $status['code'] ?? '',
            'status_name' => $status['name'] ?? '',
            'status_tone' => $status['tone'] ?? 'info',
            'is_private' => $row['is_private'],
            'end_at' => $row['accept_end_datetime'],
            'create_datetime' => $row['create_datetime'],
            'update_datetime' => $row['update_datetime'],
        );
    }
}
