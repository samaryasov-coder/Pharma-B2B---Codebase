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

    public static function replaceItems(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function replaceDocuments(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function uploadFile(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function viewAsSupplier(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::supplierCanAccess($tender, $company);
    }

    public static function apply(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::supplierCanAccess($tender, $company);
    }

    private static function supplierCanAccess(pb2bTender $tender, pb2bCompany $company): bool
    {
        if ((int) $company->id <= 0 || !(int) $tender->id) {
            return false;
        }
        if (!$company->isSupplier()) {
            return false;
        }

        $row = self::tenderRow($tender);
        if ($row === array()) {
            return false;
        }
        if (!empty($row['is_deleted'])) {
            return false;
        }
        if ((int) ($row['organizer_company_id'] ?? 0) === (int) $company->id) {
            return false;
        }
        if (!self::isSupplierVisibleStatus(self::statusCode($row))) {
            return false;
        }
        if (empty($row['is_private'])) {
            return true;
        }

        return self::hasInvitation((int) $tender->id, (int) $company->id);
    }

    /**
     * @return array<string, mixed>
     */
    private static function tenderRow(pb2bTender $tender): array
    {
        $data = $tender->data;

        return is_array($data) ? $data : array();
    }

    private static function statusCode(array $row): string
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $status_id = (int) ($row['status'] ?? 0);

        return (string) ($statuses[$status_id]['code'] ?? '');
    }

    private static function isSupplierVisibleStatus(string $code): bool
    {
        return in_array($code, array(
            'priem_zayavok',
            'vskrytie_zayavok',
            'rassmotrenie_i_dopusk',
            'peretorzhka_aktivna',
            'auktsionnye_torgi',
            'otsenka',
            'podvedenie_itogov',
            'zaklyuchenie_dogovora',
            'arkhiv',
            'priostanovlen',
            'nesostoyalsya',
        ), true);
    }

    private static function hasInvitation(int $tender_id, int $supplier_company_id): bool
    {
        if ($tender_id <= 0 || $supplier_company_id <= 0) {
            return false;
        }
        try {
            $row = (new pb2bInvitationModel())->getByField(array(
                'tender_id' => $tender_id,
                'supplier_company_id' => $supplier_company_id,
            ));
        } catch (Exception $e) {
            return false;
        }

        return is_array($row) && (int) ($row['id'] ?? 0) > 0;
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
