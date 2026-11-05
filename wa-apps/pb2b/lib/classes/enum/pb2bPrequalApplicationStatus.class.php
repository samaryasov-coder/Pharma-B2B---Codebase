<?php

enum pb2bPrequalApplicationStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case IN_REVIEW = 'in_review';
    case DECIDED = 'decided';
    case EXPIRED = 'expired';
    case WITHDRAWN = 'withdrawn';

    public static function tryFromId(int $id): ?self
    {
        $values = (array) pb2bWaproHelper::getConfigOption('prequal_application_statuses', 'id');

        return self::tryFrom((string) ($values[$id]['code'] ?? ''));
    }

    public function id(): int
    {
        $values = (array) pb2bWaproHelper::getConfigOption('prequal_application_statuses', 'code');

        return (int) ($values[$this->value]['id'] ?? 0);
    }
}
