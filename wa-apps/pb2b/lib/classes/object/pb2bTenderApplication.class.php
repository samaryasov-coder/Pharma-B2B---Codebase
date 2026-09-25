<?php

class pb2bTenderApplication extends pb2bWaproObject
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_application';
        $this->model = new pb2bTenderApplicationModel();
        parent::__construct($id);
    }

    public function getStatusCode(): string
    {
        return trim((string) ($this->data['status'] ?? ''));
    }

    public static function isAllowedStatus(string $code): bool
    {
        return in_array($code, self::codesFromConfig('tender_application_statuses'), true);
    }

    public static function isAllowedApprovalStatus(string $code): bool
    {
        return in_array($code, self::codesFromConfig('tender_application_approval_statuses'), true);
    }

    public static function isAllowedQualificationStatus(string $code): bool
    {
        return in_array($code, self::codesFromConfig('tender_application_qualification_statuses'), true);
    }

    public static function isAllowedAdmissionStatus(string $code): bool
    {
        return in_array($code, self::codesFromConfig('tender_application_admission_statuses'), true);
    }

    public static function isAllowedTransition(string $from, string $to): bool
    {
        if ($from === $to) {
            return true;
        }
        $matrix = (array) pb2bWaproHelper::getConfigOption('tender_application_status_transitions');
        $allowed = $matrix[$from] ?? array();

        return in_array($to, $allowed, true);
    }

    /**
     * @return list<string>
     */
    public static function codesFromConfig(string $option): array
    {
        $codes = array();
        foreach ((array) pb2bWaproHelper::getConfigOption($option) as $row) {
            if (is_array($row) && !empty($row['code'])) {
                $codes[] = (string) $row['code'];
            }
        }

        return $codes;
    }

    public function canSubmit(): bool
    {
        return (int) ($this->id ?? 0) > 0
            && self::isAllowedTransition($this->getStatusCode(), self::STATUS_SUBMITTED);
    }

    public function canWithdraw(): bool
    {
        return (int) ($this->id ?? 0) > 0
            && self::isAllowedTransition($this->getStatusCode(), self::STATUS_WITHDRAWN);
    }

    public function canRevertToDraft(): bool
    {
        return (int) ($this->id ?? 0) > 0
            && self::isAllowedTransition($this->getStatusCode(), self::STATUS_DRAFT);
    }

    /**
     * @return array{error: bool, message?: string}
     */
    public function applySubmit(): array
    {
        if (!$this->canSubmit()) {
            return array('error' => true, 'message' => 'Нельзя подать заявку');
        }

        $data = $this->data;
        $data['status'] = self::STATUS_SUBMITTED;
        $data['submitted_at'] = date('Y-m-d H:i:s');

        return $this->save($data);
    }

    /**
     * @return array{error: bool, message?: string}
     */
    public function applyWithdraw(): array
    {
        if (!$this->canWithdraw()) {
            return array('error' => true, 'message' => 'Нельзя отозвать заявку');
        }

        $data = $this->data;
        $data['status'] = self::STATUS_WITHDRAWN;
        $data['withdrawn_at'] = date('Y-m-d H:i:s');

        return $this->save($data);
    }

    /**
     * @return array{error: bool, message?: string}
     */
    public function applyRevertToDraft(): array
    {
        if (!$this->canRevertToDraft()) {
            return array('error' => true, 'message' => 'Нельзя вернуть заявку в черновик');
        }

        $data = $this->data;
        $data['status'] = self::STATUS_DRAFT;

        return $this->save($data);
    }

    protected function preSave(array &$data): array
    {
        $data['tender_id'] = (int) ($data['tender_id'] ?? 0);
        $data['supplier_company_id'] = (int) ($data['supplier_company_id'] ?? 0);
        $data['status'] = trim((string) ($data['status'] ?? ''));
        $data['nonprice_done'] = !empty($data['nonprice_done']) ? 1 : 0;
        $data['approval_status'] = trim((string) ($data['approval_status'] ?? ''));
        $data['qualification_status'] = trim((string) ($data['qualification_status'] ?? ''));
        $data['admission_status'] = trim((string) ($data['admission_status'] ?? ''));

        if (empty($this->id)) {
            if ($data['status'] === '') {
                $data['status'] = self::STATUS_DRAFT;
            }
            if ($data['approval_status'] === '') {
                $data['approval_status'] = 'pending';
            }
            if ($data['qualification_status'] === '') {
                $data['qualification_status'] = 'pending';
            }
            if ($data['admission_status'] === '') {
                $data['admission_status'] = 'pending';
            }
            if (empty($data['create_datetime'])) {
                $data['create_datetime'] = date('Y-m-d H:i:s');
            }
        }

        $data['update_datetime'] = date('Y-m-d H:i:s');

        if ($data['tender_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }
        if ($data['supplier_company_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указана компания-поставщик');
        }
        if (!self::isAllowedStatus($data['status'])) {
            return array('error' => true, 'message' => 'Неверный статус заявки');
        }
        if (!self::isAllowedApprovalStatus($data['approval_status'])) {
            return array('error' => true, 'message' => 'Неверный статус одобрения');
        }
        if (!self::isAllowedQualificationStatus($data['qualification_status'])) {
            return array('error' => true, 'message' => 'Неверный статус квалификации');
        }
        if (!self::isAllowedAdmissionStatus($data['admission_status'])) {
            return array('error' => true, 'message' => 'Неверный статус допуска');
        }

        if (empty($this->id)) {
            if ($data['status'] !== self::STATUS_DRAFT) {
                return array('error' => true, 'message' => 'Новая заявка создаётся только как черновик');
            }
        } else {
            $from = $this->getStatusCode();
            if ($from !== '' && !self::isAllowedTransition($from, $data['status'])) {
                return array('error' => true, 'message' => 'Недопустимый переход статуса заявки');
            }
        }

        $existing = $this->model->getByField(array(
            'tender_id' => $data['tender_id'],
            'supplier_company_id' => $data['supplier_company_id'],
        ));
        $existing_id = is_array($existing) ? (int) ($existing['id'] ?? 0) : 0;
        if ($existing_id > 0 && $existing_id !== (int) ($this->id ?? 0)) {
            return array('error' => true, 'message' => 'Заявка этой компании на тендер уже есть');
        }

        return parent::preSave($data);
    }
}
