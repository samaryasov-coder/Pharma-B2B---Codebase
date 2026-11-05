<?php

class pb2bPrequalApplicationDocument extends pb2bWaproObject
{
    protected function preSave(array &$data): array
    {
        if (!$this->id && !array_key_exists('status', $data)) {
            $data['status'] = pb2bPrequalApplicationDocumentStatus::WAITING_PROVIDER->id();
        }

        return parent::preSave($data);
    }
}
