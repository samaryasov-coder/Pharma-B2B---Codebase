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

    public static function update(pb2bPrequal $prequal, pb2bCompany $company): bool
    {
        return self::view($prequal, $company);
    }
}
