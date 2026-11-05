<?php

class pb2bTenderItem extends pb2bWaproObject
{
    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_item';
        $this->model = new pb2bTenderItemModel();
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $data['tender_id'] = (int) ($data['tender_id'] ?? 0);
        $data['name'] = trim((string) ($data['name'] ?? ''));
        $data['qty'] = (float) ($data['qty'] ?? 0);
        $data['sort'] = (int) ($data['sort'] ?? 0);

        if ($data['tender_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }
        if ($data['name'] === '') {
            return array('error' => true, 'message' => 'Укажите наименование позиции');
        }
        if ($data['qty'] <= 0) {
            return array('error' => true, 'message' => 'Количество должно быть больше нуля');
        }

        return parent::preSave($data);
    }
}
