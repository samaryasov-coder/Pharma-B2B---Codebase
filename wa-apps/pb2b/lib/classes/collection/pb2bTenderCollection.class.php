<?php

class pb2bTenderCollection extends pb2bWaproCollection
{
    public function getDataTable(array $params): array
    {
        $params['config'] = array(
            'tender_types' => (array) pb2bWaproHelper::getConfigOption('tender_types'),
            'tender_statuses' => (array) pb2bWaproHelper::getConfigOption('tender_statuses'),
        );
        return parent::getDataTable($params);
    }

    protected function setDataTableSearch(array $params): void
    {
        $s = trim((string) ($params['search'] ?? ''));
        if ($s === '') {
            return;
        }
        $this->model->addWhere(array(
            'title' => array(
                'simile' => 'LIKE',
                'value' => '%' . $s . '%',
            ),
        ));
    }

    protected function setDataTableSelect(array $params): void
    {
        $this->model->setSelect(array(
            'id' => null,
            'number' => null,
            'title' => null,
            'type' => null,
            'status' => null,
            'organizer_company_id' => null,
            'create_datetime' => null,
        ));
    }

    protected function setDataTableFields(array &$fields, array $params): void
    {
        $all = pb2bWaproHelper::getFields($this->class_name);
        $fields = array();
        foreach (array('number', 'title', 'type', 'status', 'organizer_company_id', 'create_datetime') as $code) {
            if (!empty($all[$code])) {
                $row = $all[$code];
                $row['viewed'] = true;
                $fields[$code] = $row;
            }
        }
    }

    public function getSidebarFilters(): array
    {
        $type_values = array(array('id' => '', 'name' => 'Все'));
        $types_cfg = pb2bWaproHelper::getConfigOption('tender_types');
        if (!empty($types_cfg)) {
            foreach ($types_cfg as $t) {
                if (!empty($t['name'])) {
                    $type_values[] = array('id' => (string) ($t['id'] ?? ''), 'name' => (string) $t['name']);
                }
            }
        }

        $status_values = array(array('id' => '', 'name' => 'Все'));
        $status_cfg = pb2bWaproHelper::getConfigOption('tender_statuses');
        if (!empty($status_cfg)) {
            foreach ($status_cfg as $t) {
                if (!empty($t['name'])) {
                    $status_values[] = array('id' => (string) ($t['id'] ?? ''), 'name' => (string) $t['name']);
                }
            }
        }

        return array(
            array(
                'code' => 'type',
                'type' => 'select',
                'name' => 'Тип',
                'is_opened' => 1,
                'values' => $type_values,
            ),
            array(
                'code' => 'status',
                'type' => 'select',
                'name' => 'Статус',
                'is_opened' => 1,
                'values' => $status_values,
            ),
        );
    }

    public function buildSidebarFilters(array $selected): array
    {
        $filters_def = $this->getSidebarFilters();
        foreach ($filters_def as &$f) {
            $code = (string) ($f['code'] ?? '');
            $state = $selected[$code] ?? null;

            if (($f['type'] ?? '') === 'select') {
                $f['value'] = is_scalar($state) ? (string) $state : '';
                foreach ($f['values'] ?? array() as &$v) {
                    $v_id = (string) ($v['id'] ?? '');
                    $v['checked'] = 0;
                    if (($f['value'] === '' && $v_id === '') || ($f['value'] !== '' && $v_id === $f['value'])) {
                        $v['checked'] = 1;
                    }
                }
                unset($v);
            }
        }
        unset($f);

        return $filters_def;
    }

    public function getBuyerList(int $company_id, array $filters = array()): array
    {
        if ($company_id <= 0) {
            return array();
        }

        $model = new pb2bTenderModel();
        $sql = 'SELECT id, number, title, type, status, is_private, end_at, create_datetime, update_datetime
            FROM pb2b_tender
            WHERE organizer_company_id = ? AND is_deleted = 0';
        $params = array($company_id);

        if (!empty($filters['status'])) {
            $sql .= ' AND status = ?';
            $params[] = (int) $filters['status'];
        }
        if (!empty($filters['type'])) {
            $sql .= ' AND type = ?';
            $params[] = (int) $filters['type'];
        }
        $sql .= ' ORDER BY update_datetime DESC, id DESC';

        $rows = $model->query($sql, $params)->fetchAll();
        if (!is_array($rows) || !$rows) {
            return array();
        }

        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $statuses_by_id = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');

        $ids = array_map('intval', array_column($rows, 'id'));
        $invite_counts = array();
        if ($ids) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $invite_rows = (new pb2bInvitationModel())->query(
                "SELECT tender_id, COUNT(*) AS cnt FROM pb2b_invitation WHERE tender_id IN ({$placeholders}) GROUP BY tender_id",
                $ids
            )->fetchAll();
            foreach ((array) $invite_rows as $invite_row) {
                $invite_counts[(int) $invite_row['tender_id']] = (int) $invite_row['cnt'];
            }
        }

        foreach ($rows as &$row) {
            $type_row = $types_by_id[(int) ($row['type'] ?? 0)] ?? array();
            $status_row = $statuses_by_id[(int) ($row['status'] ?? 0)] ?? array();
            $row['type_code'] = (string) ($type_row['code'] ?? '');
            $row['type_name'] = (string) ($type_row['modal_name'] ?? $type_row['name'] ?? '');
            $row['status_code'] = (string) ($status_row['code'] ?? '');
            $row['status_name'] = (string) ($status_row['name'] ?? '');
            $row['invited_count'] = (int) ($invite_counts[(int) $row['id']] ?? 0);
            // Участники / предложения — после Wave 2
            $row['participants_count'] = 0;
            $row['proposals_count'] = 0;
        }
        unset($row);

        return $rows;
    }

    public function getWithClassifiers(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }

        $model = new pb2bTenderModel();
        $tender = $model->getById($tender_id);
        if (empty($tender['id'])) {
            return array('error' => true, 'message' => 'Тендер не найден');
        }
        if (!empty($tender['is_deleted'])) {
            return array('error' => true, 'message' => 'Тендер удалён');
        }

        return array(
            'error' => false,
            'tender' => $tender,
            'classifiers' => (new pb2bTenderClassifierCollection())->getByTenderId($tender_id),
            'invitations' => pb2bTender::getInvitationsForTender($tender_id),
            'criteria' => pb2bTender::getCriteriaForTender($tender_id),
        );
    }
}
