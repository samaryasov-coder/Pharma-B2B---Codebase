<?php

enum pb2bTenderStatus: string
{
    case DRAFT = 'draft';
    case PENDING_APPROVAL = 'pending_approval';
    case PUBLISHED = 'published';
    case ACCEPTING_APPLICATIONS = 'accepting_applications';
    case OPENING_APPLICATIONS = 'opening_applications';
    case REVIEW_AND_ADMISSION = 'review_and_admission';
    case REBIDDING = 'rebidding';
    case AUCTION = 'auction';
    case EVALUATION = 'evaluation';
    case SUMMARIZING_RESULTS = 'summarizing_results';
    case CONTRACT_SIGNING = 'contract_signing';
    case ARCHIVED = 'archived';
    case CANCELLED = 'cancelled';
    case UNSUCCESSFUL = 'unsuccessful';
    case SUSPENDED = 'suspended';

    public static function tryFromId(int $id): ?self
    {
        $values = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');

        return self::tryFrom((string) ($values[$id]['code'] ?? ''));
    }

    public function id(): int
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'code');

        return (int) ($statuses[$this->value]['id'] ?? 0);
    }

    public static function isBuyerPriceVisible(string $code): bool
    {
        $status = self::tryFrom($code);

        return $status !== null && in_array($status, array(
            self::OPENING_APPLICATIONS,
            self::REVIEW_AND_ADMISSION,
            self::REBIDDING,
            self::AUCTION,
            self::EVALUATION,
            self::SUMMARIZING_RESULTS,
            self::CONTRACT_SIGNING,
            self::ARCHIVED,
        ), true);
    }

    public static function isSupplierVisible(string $code): bool
    {
        $status = self::tryFrom($code);

        return $status !== null && in_array($status, array(
            self::ACCEPTING_APPLICATIONS,
            self::OPENING_APPLICATIONS,
            self::REVIEW_AND_ADMISSION,
            self::REBIDDING,
            self::AUCTION,
            self::EVALUATION,
            self::SUMMARIZING_RESULTS,
            self::CONTRACT_SIGNING,
            self::ARCHIVED,
            self::SUSPENDED,
            self::UNSUCCESSFUL,
        ), true);
    }

    public static function isSupplierParticipationClosed(string $code): bool
    {
        $status = self::tryFrom($code);

        return $status !== null && in_array($status, array(
            self::OPENING_APPLICATIONS,
            self::REVIEW_AND_ADMISSION,
            self::REBIDDING,
            self::AUCTION,
            self::EVALUATION,
            self::SUMMARIZING_RESULTS,
            self::CONTRACT_SIGNING,
            self::ARCHIVED,
            self::CANCELLED,
            self::UNSUCCESSFUL,
        ), true);
    }
}
