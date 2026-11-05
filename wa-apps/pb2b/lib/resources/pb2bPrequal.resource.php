<?php

class pb2bPrequalResource extends pb2bBaseJsonResource
{
    public function toArray(): array
    {
        $row = $this->resource->data;
        $statuses = (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'id');
        $status_id = (int) ($row['status'] ?? 0);
        $status = $statuses[$status_id] ?? array();
        $result = array(
            'id' => (int) $this->resource->id,
            'procedure_code' => (string) ($row['procedure_code'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),
            'reviewer_id' => (int) ($row['reviewer_id'] ?? 0),
            'contact_id' => (int) ($row['contact_id'] ?? 0),
            'status' => array(
                'id' => $status_id,
                'code' => (string) ($status['code'] ?? ''),
                'name' => (string) ($status['name'] ?? ''),
            ),
            'validity_months' => isset($row['validity_months']) ? (int) $row['validity_months'] : null,
        );
        foreach (array('is_private', 'requires_audit', 'requires_samples') as $field) {
            $result[$field] = (int) ($row[$field] ?? 0);
        }
        foreach (array('accept_start_datetime', 'accept_end_datetime', 'results_planned_datatime', 'audit_info', 'samples_info', 'additional_info', 'delivery_terms', 'description', 'create_datetime', 'update_datetime') as $field) {
            $result[$field] = isset($row[$field]) ? (string) $row[$field] : null;
        }

        return $result;
    }
}
