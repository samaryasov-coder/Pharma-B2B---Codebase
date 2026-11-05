<?php

enum pb2bPrequalDecision: string
{
    case PASSED = 'passed';
    case FAILED = 'failed';
    case NEEDS_UPDATE = 'needs_update';

    public static function tryFromId(int $id): ?self
    {
        $values = (array) pb2bWaproHelper::getConfigOption('prequal_decision_types', 'id');

        return self::tryFrom((string) ($values[$id]['code'] ?? ''));
    }

    public function id(): int
    {
        $values = (array) pb2bWaproHelper::getConfigOption('prequal_decision_types', 'code');

        return (int) ($values[$this->value]['id'] ?? 0);
    }
}
