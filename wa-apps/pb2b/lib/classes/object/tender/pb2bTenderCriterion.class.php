<?php

class pb2bTenderCriterion extends pb2bWaproObject
{
    protected function preSave(array &$data): array
    {
        $data['tender_id'] = (int) ($data['tender_id'] ?? 0);
        $data['name'] = trim((string) ($data['name'] ?? ''));
        $data['type'] = trim((string) ($data['type'] ?? 'text'));
        if ($data['type'] === '' || $data['type'] === 'non_price') {
            $data['type'] = 'text';
        }
        $data['is_mandatory'] = !empty($data['is_mandatory']) ? 1 : 0;
        $data['sort'] = (int) ($data['sort'] ?? 0);
        $confirmation_text = $data['type'] === 'confirmation'
            ? trim((string) ($data['confirmation_text'] ?? '')) : '';
        $data['confirmation_text'] = $confirmation_text !== '' ? $confirmation_text : null;

        if ($data['tender_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }
        if (!in_array($data['type'], array('text', 'date', 'file', 'confirmation'), true)) {
            return array('error' => true, 'message' => 'Недопустимый тип ответа на критерий');
        }

        return parent::preSave($data);
    }
}
