<?php

enum pb2bPrequalApplicationDocumentStatus: string
{
    case WAITING_PROVIDER = 'waiting_provider';
    case UPLOADED = 'uploaded';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public static function tryFromId(int $id): ?self
    {
        $values = (array) pb2bWaproHelper::getConfigOption('prequal_application_document_statuses', 'id');

        return self::tryFrom((string) ($values[$id]['code'] ?? ''));
    }

    public function id(): int
    {
        $values = (array) pb2bWaproHelper::getConfigOption('prequal_application_document_statuses', 'code');

        return (int) ($values[$this->value]['id'] ?? 0);
    }
}
