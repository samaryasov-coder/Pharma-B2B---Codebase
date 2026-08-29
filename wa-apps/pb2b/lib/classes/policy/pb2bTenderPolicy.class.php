<?php

class pb2bTenderPolicy
{
    public static function create(pb2bCompany $company): bool
    {
        return $company->isBuyer();
    }

    public static function view(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function update(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function publish(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function replaceCriteria(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function replaceInvitations(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function replaceClassifiers(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    private static function ownsActive(pb2bTender $tender, pb2bCompany $company): bool
    {
        if ((int) $company->id <= 0) {
            return false;
        }
        if ((int) ($tender->data['organizer_company_id'] ?? 0) !== (int) $company->id) {
            return false;
        }
        if (!empty($tender->data['is_deleted'])) {
            return false;
        }

        return true;
    }
}
