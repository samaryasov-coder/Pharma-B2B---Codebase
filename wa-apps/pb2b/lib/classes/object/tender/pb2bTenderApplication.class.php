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

    public function getItems(): array
    {
        return (new pb2bTenderApplicationItemCollection())->getByApplicationId((int) $this->id);
    }

    public function getDocuments(): array
    {
        return (new pb2bTenderApplicationDocumentCollection())->getByApplicationId((int) $this->id);
    }

    public function getCriteria(): array
    {
        return (new pb2bTenderApplicationCriterionCollection())->getByApplicationId((int) $this->id);
    }

    public function getStatusCode(): string
    {
        return trim((string) ($this->data['status'] ?? ''));
    }

    public static function isAllowedStatus(string $code): bool
    {
        return isset(pb2bWaproHelper::getConfigOption('tender_application_statuses', 'code')[$code]);
    }

    public static function isAllowedApprovalStatus(string $code): bool
    {
        return isset(pb2bWaproHelper::getConfigOption('tender_application_approval_statuses', 'code')[$code]);
    }

    public static function isAllowedQualificationStatus(string $code): bool
    {
        return isset(pb2bWaproHelper::getConfigOption('tender_application_qualification_statuses', 'code')[$code]);
    }

    public static function isAllowedAdmissionStatus(string $code): bool
    {
        return isset(pb2bWaproHelper::getConfigOption('tender_application_admission_statuses', 'code')[$code]);
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

    public function canSubmit(): bool
    {
        return (int) ($this->id ?? 0) > 0
            && self::isAllowedTransition($this->getStatusCode(), self::STATUS_SUBMITTED);
    }

    public function canEdit(pb2bTender $tender): bool
    {
        return $this->canChangeInTender($tender)
            && ($this->getStatusCode() === self::STATUS_DRAFT
                || ($this->getStatusCode() === self::STATUS_SUBMITTED && $this->canRevertToDraft()));
    }

    private function canChangeInTender(pb2bTender $tender): bool
    {
        return (int) $this->id > 0
            && (int) $this->data['tender_id'] === (int) $tender->id
            && pb2bTenderStatus::tryFromId((int) $tender->data['status']) === pb2bTenderStatus::ACCEPTING_APPLICATIONS
            && !$tender->isReceptionDeadlinePassed();
    }

    public function canWithdraw(?pb2bTender $tender = null): bool
    {
        return (int) ($this->id ?? 0) > 0
            && ($tender === null || $this->canChangeInTender($tender))
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

        $winner_via_service = !empty($data['_winner_via_service']);
        unset($data['_winner_via_service']);
        if (empty($this->id)) {
            $data['is_winner'] = !empty($data['is_winner']) ? 1 : 0;
        } elseif (!$winner_via_service) {
            $data['is_winner'] = !empty($this->data['is_winner']) ? 1 : 0;
        } else {
            $new_winner = !empty($data['is_winner']) ? 1 : 0;
            $old_winner = !empty($this->data['is_winner']) ? 1 : 0;
            if ($new_winner !== $old_winner && $this->isTenderWinnerSelectionCompleted((int) $data['tender_id'])) {
                return array('error' => true, 'message' => 'Выбор победителя уже завершён');
            }
            $data['is_winner'] = $new_winner;
        }

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
        }

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

    private function isTenderWinnerSelectionCompleted(int $tender_id): bool
    {
        if ($tender_id <= 0) {
            return false;
        }
        $tender = new pb2bTender($tender_id);
        if (!(int) $tender->id) {
            return false;
        }

        return !empty($tender->data['winner_selection_completed']);
    }
}
