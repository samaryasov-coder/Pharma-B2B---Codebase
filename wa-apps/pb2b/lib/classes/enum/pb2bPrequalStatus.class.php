<?php

enum pb2bPrequalStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ACCEPTING_APPLICATIONS = 'accepting_applications';
    case REVIEW = 'review';
    case COMPLETED = 'completed';
    case ARCHIVED = 'archived';
    case CANCELLED = 'cancelled';
    case SUSPENDED = 'suspended';

    public static function tryFromId(int $id): ?self
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'id');
        return self::tryFrom((string) ($statuses[$id]['code'] ?? ''));
    }

    public function id(): int
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'code');

        return (int) ($statuses[$this->value]['id'] ?? 0);
    }
}
