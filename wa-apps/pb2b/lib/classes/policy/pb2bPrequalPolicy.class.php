<?php

class pb2bPrequalPolicy
{
    public static function create(pb2bCompany $company): bool
    {
        return (int) $company->id > 0 && $company->isBuyer();
    }

    public static function view(pb2bPrequal $prequal, pb2bCompany $company): bool
    {
        return (int) $company->id > 0
            && $company->isBuyer()
            && (int) ($prequal->data['reviewer_id'] ?? 0) === (int) $company->id;
    }

    public static function viewAsSupplier(pb2bPrequal $prequal, pb2bCompany $company, bool $has_invitation): bool
    {
        return self::viewSupplierData($prequal->data, $company, $has_invitation);
    }

    public static function viewSupplierData(array $prequal, pb2bCompany $company, bool $has_invitation): bool
    {
        $status = pb2bPrequalStatus::tryFromId((int) ($prequal['status'] ?? 0));
        return (int) ($prequal['id'] ?? 0) > 0 && (int) $company->id > 0 && $company->isSupplier()
            && (int) ($prequal['reviewer_id'] ?? 0) !== (int) $company->id
            && $status !== null && $status !== pb2bPrequalStatus::DRAFT
            && (empty($prequal['is_private']) || $has_invitation);
    }

    public static function update(pb2bPrequal $prequal, pb2bCompany $company): bool
    {
        return self::view($prequal, $company);
    }
}
