<?php

class pb2bPrequal extends pb2bWaproObject
{
    public function getInvitations(): array
    {
        return (new pb2bPrequalInvitationCollection())->getByPrequalId((int) $this->id);
    }

    public function getApplications(): array
    {
        return (new pb2bPrequalApplicationCollection())->getByPrequalId((int) $this->id);
    }

    public function getItems(): array
    {
        return (new pb2bPrequalItemCollection())->getByPrequalId((int) $this->id);
    }

    public function getDocuments(): array
    {
        return (new pb2bPrequalDocumentCollection())->getByPrequalId((int) $this->id);
    }

    public function getClassifiers(): array
    {
        return (new pb2bPrequalClassifierCollection())->getByPrequalId((int) $this->id);
    }

    public function canEdit(): bool
    {
        return (int) $this->id > 0 && pb2bPrequalStatus::tryFromId((int) ($this->data['status'] ?? 0)) === pb2bPrequalStatus::DRAFT;
            
    }
}
