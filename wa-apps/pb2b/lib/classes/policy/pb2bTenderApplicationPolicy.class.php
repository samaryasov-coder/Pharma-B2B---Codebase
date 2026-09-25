<?php

class pb2bTenderApplicationPolicy
{
    public static function view(pb2bTenderApplication $application, pb2bCompany $company): bool
    {
        return self::ownsAsSupplier($application, $company);
    }

    public static function update(pb2bTenderApplication $application, pb2bCompany $company): bool
    {
        return self::ownsAsSupplier($application, $company);
    }

    public static function submit(pb2bTenderApplication $application, pb2bCompany $company): bool
    {
        return self::ownsAsSupplier($application, $company);
    }

    public static function withdraw(pb2bTenderApplication $application, pb2bCompany $company): bool
    {
        return self::ownsAsSupplier($application, $company);
    }

    public static function viewAsBuyer(pb2bTenderApplication $application, pb2bCompany $company): bool
    {
        $tender = self::tenderOf($application);
        if (!$tender) {
            return false;
        }

        return pb2bTenderPolicy::view($tender, $company);
    }

    private static function ownsAsSupplier(pb2bTenderApplication $application, pb2bCompany $company): bool
    {
        if ((int) $company->id <= 0 || (int) $application->id <= 0) {
            return false;
        }
        if (!$company->isSupplier()) {
            return false;
        }
        $row = self::applicationRow($application);
        if ((int) ($row['supplier_company_id'] ?? 0) !== (int) $company->id) {
            return false;
        }

        return true;
    }

    private static function tenderOf(pb2bTenderApplication $application): ?pb2bTender
    {
        $row = self::applicationRow($application);
        $tender_id = (int) ($row['tender_id'] ?? 0);
        if ($tender_id <= 0) {
            return null;
        }
        $tender = new pb2bTender($tender_id);
        if (!(int) $tender->id) {
            return null;
        }

        return $tender;
    }

    /**
     * @return array<string, mixed>
     */
    private static function applicationRow(pb2bTenderApplication $application): array
    {
        $data = $application->data;

        return is_array($data) ? $data : array();
    }
}
