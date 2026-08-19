<?php

class pb2bProcedure extends pb2bWaproObject
{
    protected function preSave(array &$data): array
    {
        foreach (array('is_private', 'retendering_enabled', 'itemized_enabled', 'approval_required', 'is_deleted') as $flag) {
            if (!array_key_exists($flag, $data)) {
                $data[$flag] = 0;
            }
        }
        if (array_key_exists('submission_form', $data) && $data['submission_form'] === '') {
            $data['submission_form'] = null;
        }
        if (empty($this->id)) {
            $statuses = pb2bWaproHelper::getConfigOption('procedure_statuses', 'code');
            $draft_id = (int) ($statuses['draft']['id'] ?? 1);
            if (empty($data['status'])) {
                $data['status'] = $draft_id;
            }
            if (empty($data['currency'])) {
                $data['currency'] = 'RUB';
            }
        }
        return parent::preSave($data);
    }

    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);
        if (empty($result['error']) && !empty($result['new'])) {
            $result['dispatch_url'] = '#/procedure/edit/id='.$this->id;
        }
    }
}
