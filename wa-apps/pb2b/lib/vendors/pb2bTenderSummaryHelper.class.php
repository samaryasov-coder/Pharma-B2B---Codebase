<?php

class pb2bTenderSummaryHelper
{
    public static function supplierNotice(array $items, array $classifiers): array
    {
        $place = '';
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $place = trim((string) ($item['delivery_place'] ?? ''));
            if ($place !== '') {
                break;
            }
        }

        $category = '';
        $category_type_id = (int) (pb2bWaproHelper::getConfigOption('tender_classifier_types', 'code')['category']['id'] ?? 0);
        foreach ($classifiers as $classifier) {
            if (!is_array($classifier) || (int) ($classifier['classifier_type'] ?? 0) !== $category_type_id) {
                continue;
            }
            $category = trim((string) ($classifier['category_name'] ?? ''));
            if ($category !== '') {
                break;
            }
        }

        return array(
            'place' => $place,
            'category' => $category,
        );
    }
}
