<?php

class pb2bTenderClassifierResource extends pb2bBaseJsonResource
{
    public function toArray(): array
    {
        $row = $this->resource;
        $types = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'id');
        $type = $types[(int) $row['classifier_type']] ?? array('code' => '', 'name' => '');

        return array(
            'id' => (int) $row['id'],
            'tender_id' => (int) $row['tender_id'],
            'classifier_type' => (int) $row['classifier_type'],
            'classifier_id' => (int) $row['classifier_id'],
            'classifier_type_code' => (string) $type['code'],
            'classifier_type_name' => (string) $type['name'],
            'classifier_name' => match ($type['code']) {
                'esklp' => (string) ($row['esklp_name'] ?? ''),
                'category' => (string) ($row['category_name'] ?? ''),
                default => '',
            },
        );
    }
}
