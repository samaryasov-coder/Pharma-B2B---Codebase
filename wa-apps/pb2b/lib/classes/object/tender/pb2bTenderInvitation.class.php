<?php

class pb2bTenderInvitation extends pb2bWaproObject
{
    public const STATUS_INVITED = 'invited';

    protected function preSave(array &$data): array
    {
        $data['tender_id'] = (int) ($data['tender_id'] ?? 0);
        $data['supplier_company_id'] = (int) ($data['supplier_company_id'] ?? 0);
        if ($data['tender_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }
        if ($data['supplier_company_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан поставщик');
        }

        return parent::preSave($data);
    }
}
