<?php

enum pb2bTenderType: string
{
    case PREQUALIFICATION = 'prequalification';
    case QUICK_PURCHASE = 'quick_purchase';
    case PRICE_REQUEST = 'price_request';
    case SINGLE_SUPPLIER = 'single_supplier';
    case PROPOSAL_REQUEST = 'proposal_request';
    case AUCTION = 'auction';

    public static function tryFromId(int $id): ?self
    {
        $values = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        return self::tryFrom((string) ($values[$id]['code'] ?? ''));
    }

    public function id(): int
    {
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'code');

        return (int) ($types[$this->value]['id'] ?? 0);
    }
}
