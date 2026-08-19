<?php

class pb2bSettings
{
    private array $config;
    private waAppSettingsModel $asm;

    public function __construct()
    {
        $this->config = (array) pb2bWaproHelper::getConfigOption('settings');
        $this->asm = new waAppSettingsModel();
    }

    public function getValue(string $type, string $code, $default = ''): string
    {
        return (string)$this->asm->get('pb2b', $type.'.'.$code, $default);
    }

    public function getPageData(string $type): array
    {
        $page = $this->findPageByType($type);
        if (!$page) {
            throw new waException('Раздел настроек не найден: '.$type);
        }

        $fields = (array) ($page['fields'] ?? []);
        $values = [];

        foreach ($fields as $code => $field) {
            $default = $field['default'] ?? '';
            $values[$code] = $this->asm->get('pb2b', $type.'.'.$code, $default);
        }

        return array(
            'page' => $page,
            'type' => $type,
            'values' => $values,
        );
    }

    public function save(string $type, array $posted, array $files = []): array
    {
        $page = $this->findPageByType($type);
        if (!$page) {
            return ['error' => 1, 'message' => 'Раздел настроек не найден: '.$type];
        }

        $fields = (array) ($page['fields'] ?? []);
        foreach ($fields as $code => $field) {
            if (empty($field['required'])) continue;
            $v = $posted[$code] ?? null;
            $is_empty = ($v === null || $v === '' || $v === []);
            if ($is_empty) {
                $name = $field['name'] ?? $code;
                return ['error' => 1, 'message' => 'Поле обязательно: '.$name];
            }
        }

        foreach ($fields as $code => $field) {
            if (($field['field']['tag'] ?? '') === 'input' && (($field['field']['type'] ?? '') === 'file')) {
                continue;
            }

            $val = $posted[$code] ?? '';
            
            if (is_array($val)) {
                $val = json_encode(array_values($val), JSON_UNESCAPED_UNICODE);
            }

            $this->asm->set('pb2b', $type.'.'.$code, (string) $val);
        }

        return ['error' => 0, 'message' => 'Настройки сохранены'];
    }

    private function findPageByType(string $type): ?array
    {
        // 1) если ключ совпадает с type
        if (isset($this->config[$type]) && is_array($this->config[$type])) {
            $p = $this->config[$type];
            $p['type'] = $p['type'] ?? $type;
            return $p;
        }

        // 2) иначе ищем по полю type
        foreach ($this->config as $key => $p) {
            if (!is_array($p)) continue;
            $p_type = $p['type'] ?? $key;
            if ($p_type === $type) {
                $p['type'] = $p_type;
                return $p;
            }
        }

        return null;
    }
}
