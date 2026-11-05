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
        if ($company_id <= 0) return array();

        $where = array(
            'organizer_company_id' => array('value' => $company_id),
            'is_deleted' => array('simile' => 'IN', 'value' => array(0)),
        );
        if (!empty($filters['status'])) {
            $where['status'] = array('value' => (int) $filters['status']);
        }
        if (!empty($filters['type'])) {
            $where['type'] = array('value' => (int) $filters['type']);
        }
        $this->model->setWhere($where);
        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'number' => null,
                'title' => null,
                'type' => null,
                'status' => null,
                'is_private' => null,
                'end_at' => null,
                'create_datetime' => null,
                'update_datetime' => null,
            ),
            'order' => array('update_datetime' => 'DESC', 'id' => 'DESC'),
        ));
    }
}
