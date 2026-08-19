<?php

class pb2bTenderClassifierCollection extends pb2bWaproCollection
{
    public function __construct($hash = null)
    {
        $this->class_name = 'tender_classifier';
        $this->model = new pb2bTenderClassifierModel();
        parent::__construct($hash);
    }

    public function getByTenderId(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array();
        }

        $model = new pb2bTenderClassifierModel();
        $rows = $model->query(
            'SELECT id, tender_id, classifier_type, classifier_id
             FROM pb2b_tender_classifier
             WHERE tender_id = ?
             ORDER BY classifier_type ASC, classifier_id ASC',
            $tender_id
        )->fetchAll();
        return $this->enrichClassifierRows(is_array($rows) ? $rows : array());
    }

    public function enrichClassifierRows(array $rows): array
    {
        if (empty($rows)) {
            return array();
        }

        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'id');
        $esklp_ids = array();
        $okpd2_ids = array();

        foreach ($rows as &$row) {
            $type_id = (int) ($row['classifier_type'] ?? 0);
            $type_row = $types_by_id[$type_id] ?? null;
            $code = (string) ($type_row['code'] ?? '');
            $row['classifier_type_code'] = $code;
            $row['classifier_type_name'] = (string) ($type_row['name'] ?? '');
            $row['classifier_name'] = '';

            $node_id = (int) ($row['classifier_id'] ?? 0);
            if ($code === 'esklp' && $node_id > 0) {
                $esklp_ids[$node_id] = $node_id;
            } elseif ($code === 'okpd2' && $node_id > 0) {
                $okpd2_ids[$node_id] = $node_id;
            }
        }
        unset($row);

        $esklp_names = $this->loadEsklpNames($esklp_ids);
        $okpd2_names = $this->loadOkpd2Names($okpd2_ids);

        foreach ($rows as &$row) {
            $node_id = (int) ($row['classifier_id'] ?? 0);
            $code = (string) ($row['classifier_type_code'] ?? '');
            if ($code === 'esklp') {
                $row['classifier_name'] = (string) ($esklp_names[$node_id] ?? '');
            } elseif ($code === 'okpd2') {
                $row['classifier_name'] = (string) ($okpd2_names[$node_id] ?? '');
            }
        }
        unset($row);

        return $rows;
    }

    private function loadEsklpNames(array $ids): array
    {
        if (empty($ids)) {
            return array();
        }
        $model = new pb2bEsklpModel();
        $names = array();
        foreach ($ids as $id) {
            $row = $model->getById((int) $id);
            if (!empty($row['name'])) {
                $names[(int) $id] = (string) $row['name'];
            }
        }
        return $names;
    }

    private function loadOkpd2Names(array $ids): array
    {
        if (empty($ids)) {
            return array();
        }
        try {
            $model = new waModel();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $rows = $model->query(
                'SELECT id, name FROM pb2b_okpd2 WHERE id IN ('.$placeholders.')',
                array_values($ids)
            )->fetchAll('id');
            $names = array();
            foreach ($rows as $id => $row) {
                if (!empty($row['name'])) {
                    $names[(int) $id] = (string) $row['name'];
                }
            }
            return $names;
        } catch (Exception $e) {
            return array();
        }
    }
}
