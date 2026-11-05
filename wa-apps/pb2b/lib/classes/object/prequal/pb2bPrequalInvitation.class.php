<?php

class pb2bPrequalInvitation extends pb2bWaproObject
{
    public const STATUS_INVITED = 'invited';

    protected function preSave(array &$data): array
    {
        if (!$this->id && !array_key_exists('status', $data)) {
            $data['status'] = self::STATUS_INVITED;
        }

        return parent::preSave($data);
    }
}
