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

    public static function startReview(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    /** Черновик выбора победителя: select all/positional, clear. Тип/статус — в Service. */
    public static function selectWinner(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    /** Завершение выбора / finish-without → переходы статуса. */
    public static function completeWinner(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::ownsActive($tender, $company);
    }

    public static function replaceItems(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::update($tender, $company);
    }

    public static function replaceDocuments(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::update($tender, $company);
    }

    public static function uploadFile(pb2bTender $tender, pb2bCompany $company): bool
    {
        return self::update($tender, $company);
    }

    public static function viewAsSupplier(pb2bTender $tender, pb2bCompany $company, bool $has_invitation): bool
    {
        return self::supplierCanAccess($tender, $company, $has_invitation);
    }

    public static function apply(pb2bTender $tender, pb2bCompany $company, bool $has_invitation): bool
    {
        return self::supplierCanAccess($tender, $company, $has_invitation);
    }

    private static function supplierCanAccess(pb2bTender $tender, pb2bCompany $company, bool $has_invitation): bool
    {
        if ((int) $company->id <= 0 || !(int) $tender->id) return false;
        if (!$company->isSupplier()) return false;

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

        return $has_invitation;
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
        return pb2bTenderStatus::isSupplierVisible($code);
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
