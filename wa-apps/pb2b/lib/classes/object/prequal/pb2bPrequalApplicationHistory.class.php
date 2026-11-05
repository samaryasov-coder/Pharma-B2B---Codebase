<?php

class pb2bPrequalApplicationHistory extends pb2bWaproObject
{
    protected function preSave(array &$data): array
    {
        if ($this->id) {
            return array('error' => true, 'message' => 'Историю пакета ПК нельзя изменять');
        }
        if (array_key_exists('data_json', $data) && $data['data_json'] !== null) {
            if (!is_string($data['data_json'])) {
                return array('error' => true, 'message' => 'Данные истории должны быть строкой JSON');
            }
            try {
                json_decode($data['data_json'], true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException $e) {
                return array('error' => true, 'message' => 'Некорректный JSON истории пакета ПК');
            }
        }

        return parent::preSave($data);
    }

    protected function preDelete(array &$data = array()): array
    {
        return array('error' => true, 'message' => 'Историю пакета ПК нельзя удалять');
    }
}
