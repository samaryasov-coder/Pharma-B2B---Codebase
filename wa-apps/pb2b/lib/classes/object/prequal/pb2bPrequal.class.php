<?php

class pb2bPrequal extends pb2bWaproObject
{
    public static function getCreateSteps(): array
    {
        return array(
            array('key' => 'access', 'label' => 'Доступ к процедуре', 'available' => true),
            array('key' => 'basic', 'label' => 'Основные данные', 'available' => true),
            array('key' => 'conditions', 'label' => 'Условия квалификации и сроки', 'available' => true),
            array('key' => 'contacts', 'label' => 'Адреса и контакты', 'available' => true),
            array('key' => 'items', 'label' => 'Предметы и документы', 'available' => true),
            array('key' => 'invitation', 'label' => 'Рассылка и публикация', 'available' => true),
        );
    }

    public function getInvitations(): array
    {
        return (new pb2bPrequalInvitationCollection())->getByPrequalId((int) $this->id);
    }

    public function getApplications(): array
    {
        return (new pb2bPrequalApplicationCollection())->getByPrequalId((int) $this->id);
    }

    public function getApplicationBySupplier(int $company_id): array
    {
        return (array) (new pb2bPrequalApplicationModel())->getByField(array(
            'prequal_id' => (int) $this->id,
            'supplier_company_id' => $company_id,
        ));
    }

    public function canAcceptApplications(): bool
    {
        $status = pb2bPrequalStatus::tryFromId((int) ($this->data['status'] ?? 0));
        $now = (new DateTimeImmutable('now', new DateTimeZone('Europe/Moscow')))->format('Y-m-d H:i:s');

        return in_array($status, array(pb2bPrequalStatus::PUBLISHED, pb2bPrequalStatus::ACCEPTING_APPLICATIONS), true)
            && ($this->data['accept_start_datetime'] ?? '') <= $now
            && ($this->data['accept_end_datetime'] ?? '') > $now;
    }

    public function getItems(): array
    {
        return (new pb2bPrequalItemCollection())->getByPrequalId((int) $this->id);
    }

    public function getDocuments(?string $kind = null): array
    {
        return (new pb2bPrequalDocumentCollection())->getByPrequalId((int) $this->id, $kind);
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
