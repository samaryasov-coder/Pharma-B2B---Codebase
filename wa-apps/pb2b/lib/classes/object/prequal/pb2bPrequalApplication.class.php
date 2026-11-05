<?php

class pb2bPrequalApplication extends pb2bWaproObject
{
    public function getItems(): array
    {
        return (new pb2bPrequalApplicationItemCollection())->getByApplicationId((int) $this->id);
    }

    public function getDocuments(): array
    {
        return (new pb2bPrequalApplicationDocumentCollection())->getByApplicationId((int) $this->id);
    }

    public function getHistory(): array
    {
        return (new pb2bPrequalApplicationHistoryCollection())->getByApplicationId((int) $this->id);
    }

    public function canEdit(): bool
    {
        return (int) $this->id > 0
            && pb2bPrequalApplicationStatus::tryFromId((int) ($this->data['status'] ?? 0)) === pb2bPrequalApplicationStatus::DRAFT;
    }

    protected function preSave(array &$data): array
    {
        if (!$this->id && !array_key_exists('status', $data)) {
            $data['status'] = pb2bPrequalApplicationStatus::DRAFT->id();
        }

        return parent::preSave($data);
    }
}
