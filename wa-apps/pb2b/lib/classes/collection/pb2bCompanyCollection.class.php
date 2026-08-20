<?php

class pb2bCompanyCollection extends pb2bWaproCollection
{
    public function getSidebarFilters(): array
    {
        return array(
            array(
                'code' => 'type',
                'type' => 'select',
                'name' => 'Тип',
                'is_opened' => 1,
                'values' => array(
                    array('id' => '', 'name' => 'Все'),
                    array('id' => 'buyer', 'name' => 'Покупатель'),
                    array('id' => 'supplier', 'name' => 'Поставщик'),
                ),
            ),
            
            array(
                'code' => 'status',
                'type' => 'checkbox',
                'name' => 'Статус',
                'values' => array(
                    array('id' => '0', 'name' => 'Не активна'),
                    array('id' => '1', 'name' => 'Активна'),
                ),
            ),
            
            array(
                'code' => 'create_datetime',
                'type' => 'date-interval',
                'name' => 'Дата добавления'
            ),
            
        );
    }

    public function buildSidebarFilters(array $selected): array
    {
        $filters_def = $this->getSidebarFilters();
        foreach ($filters_def as &$f) {
            $code = (string)($f['code'] ?? '');
            $type = (string)($f['type'] ?? 'input');
            $state = $selected[$code] ?? null;

            if ($type === 'select') {
                $f['value'] = is_scalar($state) ? (string)$state : '';
                if (!empty($f['values'])) {
                    foreach ($f['values'] as &$v) {
                        $v_id = (string)($v['id'] ?? '');
                        $v['checked'] = 0;
                        if ($f['value'] === '' && $v_id === '') { $v['checked'] = 1; }
                        if ($f['value'] !== '' && $v_id === $f['value']) { $v['checked'] = 1; }
                    }
                    unset($v);
                }
            } elseif ($type === 'checkbox') {
                $selected_ids = array();
                if (is_array($state)) {
                    foreach ($state as $k => $v) {
                        if ((string)$v !== '' && (string)$v !== '0') {
                            $selected_ids[(string)$k] = 1;
                        }
                    }
                }
                if (!empty($f['values'])) {
                    foreach ($f['values'] as &$v) {
                        $v_id = (string)($v['id'] ?? '');
                        $v['checked'] = isset($selected_ids[$v_id]) ? 1 : 0;
                    }
                    unset($v);
                }
            } elseif ($type === 'date-interval') {
                $f['from'] = (string)($state['from'] ?? '');
                $f['to'] = (string)($state['to'] ?? '');
            } else {
                $f['value'] = is_scalar($state) ? (string)$state : '';
            }
        }
        unset($f);

        return $filters_def;
    }

}
