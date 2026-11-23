<?php

class pb2bPrequalDocument extends pb2bWaproObject
{
    public const KIND_TECH_SPEC = 'tech_spec';
    public const KIND_REQUIREMENT = 'requirement';

    protected function preSave(array &$data): array
    {
        $data['kind'] = $data['kind'] ?? self::KIND_REQUIREMENT;
        $data['is_required'] = !empty($data['is_required']) ? 1 : 0;
        if (!in_array($data['kind'], array(self::KIND_TECH_SPEC, self::KIND_REQUIREMENT), true)) return array('error' => true, 'message' => 'Неверный вид документа ПК');
        if ($data['kind'] === self::KIND_TECH_SPEC && empty($data['file_link_id'])) return array('error' => true, 'message' => 'Не указан файл документации ПК');
        return parent::preSave($data);
    }
}
