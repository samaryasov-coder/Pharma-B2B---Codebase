<?php

class pb2bTender extends pb2bWaproObject
{
    private const MVP_TYPE_CODES = array('prequalification', 'price_request');

    private const TYPE_OPTIONAL_WIZARD_STEPS = array('privacy', 'payment_delivery');

    private const WIZARD_STEP_FIELDS = array(
        'privacy' => array('is_private', 'approval_required', 'type', 'submission_form'),
        'basic' => array('title', 'number', 'type', 'submission_form'),
        'purchase_params' => array(
            'prequal_validity_months', 'retendering_enabled', 'itemized_enabled',
            'start_at', 'end_at', 'opening_at', 'type',
        ),
        'payment_delivery' => array('payment_terms', 'delivery_terms', 'budget', 'currency'),
        'lots' => array(),
        'invitation' => array(),
    );

    private const PREQUAL_FORBIDDEN_FIELDS = array(
        'retendering_enabled', 'itemized_enabled', 'budget',
    );

    private static function normalizeDatetimeFieldForMysql($raw): array
    {
        if ($raw === null || $raw === '') {
            return array(true, null);
        }
        if (!is_scalar($raw)) {
            return array(false, null);
        }
        $v = trim((string) $raw);
        if ($v === '') {
            return array(true, null);
        }
        foreach (
            array(
                'Y-m-d\TH:i:s',
                'Y-m-d\TH:i',
                'Y-m-d H:i:s',
                'Y-m-d H:i',
                'Y-m-d',
            ) as $fmt
        ) {
            $dt = DateTimeImmutable::createFromFormat($fmt, $v);
            if ($dt instanceof DateTimeImmutable) {
                $le = DateTimeImmutable::getLastErrors();
                if (empty($le['warning_count']) && empty($le['error_count'])) {
                    return array(true, $dt->format('Y-m-d H:i:s'));
                }
            }
        }

        return array(false, null);
    }

    public static function formatDatetimeLocalAttr($mysql): string
    {
        if ($mysql === null || $mysql === '') {
            return '';
        }
        if (!is_scalar($mysql)) {
            return '';
        }
        $v = trim((string) $mysql);
        if ($v === '') {
            return '';
        }
        foreach (array('Y-m-d H:i:s', 'Y-m-d H:i', 'Y-m-d') as $fmt) {
            $dt = DateTimeImmutable::createFromFormat($fmt, $v);
            if ($dt instanceof DateTimeImmutable) {
                $le = DateTimeImmutable::getLastErrors();
                if (empty($le['warning_count']) && empty($le['error_count'])) {
                    return $dt->format('Y-m-d\TH:i:s');
                }
            }
        }

        return '';
    }

    protected function preSave(array &$data): array
    {
        $wizard_step = isset($data['_wizard_step']) ? (string) $data['_wizard_step'] : null;
        unset($data['_wizard_step']);

        $status_via_service = !empty($data['_status_via_service']);
        unset($data['_status_via_service']);
        if (!empty($this->id) && array_key_exists('status', $data) && !$status_via_service) {
            $new_status = (int) $data['status'];
            $old_status = (int) ($this->data['status'] ?? 0);
            if ($new_status !== $old_status && wa()->getEnv() !== 'backend') {
                return array('error' => true, 'message' => 'Смена статуса доступна через публикацию или согласование');
            }
        }

        if (empty($this->id)) {
            foreach (array('is_private', 'retendering_enabled', 'itemized_enabled', 'approval_required', 'is_deleted') as $flag) {
                if (!array_key_exists($flag, $data)) {
                    $data[$flag] = 0;
                }
            }
        }
        if (array_key_exists('submission_form', $data) && $data['submission_form'] === '') {
            $data['submission_form'] = null;
        }
        if (empty($this->id)) {
            $statuses = pb2bWaproHelper::getConfigOption('tender_statuses', 'code');
            $draft_id = (int) ($statuses['draft']['id'] ?? 1);
            if (empty($data['status'])) {
                $data['status'] = $draft_id;
            }
            if (empty($data['currency'])) {
                $data['currency'] = 'RUB';
            }
        }

        if (wa()->getEnv() === 'frontend') {
            $buyer_check = $this->assertBuyerInCabinetContext();
            if ($buyer_check !== null) {
                return $buyer_check;
            }
        }

        $type_check = $this->applyTypeRules($data, $wizard_step);
        if ($type_check !== null) {
            return $type_check;
        }

        $tender_fields = pb2bWaproHelper::getFields('tender');
        if (!is_array($tender_fields)) {
            $tender_fields = array();
        }
        foreach (array('start_at', 'end_at', 'opening_at') as $dt_field) {
            if (!array_key_exists($dt_field, $data)) {
                continue;
            }
            list($ok, $mysql_dt) = self::normalizeDatetimeFieldForMysql($data[$dt_field]);
            if (!$ok) {
                $label = isset($tender_fields[$dt_field]['name']) ? $tender_fields[$dt_field]['name'] : $dt_field;

                return array(
                    'error' => true,
                    'message' => 'поле "'.$label.'" должно быть пустым или корректной датой/временем',
                );
            }
            $data[$dt_field] = $mysql_dt;
        }

        return parent::preSave($data);
    }

    private function assertBuyerInCabinetContext(): ?array
    {
        $company = pb2bCabinetContextFactory::build()->company();
        if (!$company || !$company->id) {
            return array('error' => true, 'message' => 'Компания не выбрана');
        }
        if (!$company->isBuyer()) {
            return array('error' => true, 'message' => 'Создавать тендер может только компания-покупатель');
        }
        return null;
    }

    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);
        if (empty($result['error']) && !empty($result['new'])) {
            $result['dispatch_url'] = '#/tender/edit/id='.$this->id;
        }
    }

    private function applyTypeRules(array &$data, ?string $wizard_step = null): ?array
    {
        $type_id = $this->resolveTypeId($data);
        if ($type_id <= 0) {
            if (empty($this->id)) {
                if ($wizard_step !== null && in_array($wizard_step, self::TYPE_OPTIONAL_WIZARD_STEPS, true)) {
                    return null;
                }
                return array('error' => true, 'message' => 'Не указан тип процедуры');
            }
            return null;
        }

        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $type_row = $types_by_id[$type_id] ?? null;
        if (empty($type_row['code'])) {
            return array('error' => true, 'message' => 'Неверный тип процедуры');
        }

        $type_code = (string) $type_row['code'];
        $mvp_check = $this->assertMvpTypeAllowed($type_code, $data, $type_id);
        if ($mvp_check !== null) {
            return $mvp_check;
        }

        $prequal_check = $this->rejectPrequalPriceFields($type_code, $data);
        if ($prequal_check !== null) {
            return $prequal_check;
        }

        return $this->enforceOptionsForTypeCode($type_code, $data);
    }

    private function rejectPrequalPriceFields(string $type_code, array $data): ?array
    {
        if ($type_code !== 'prequalification') {
            return null;
        }
        foreach (self::PREQUAL_FORBIDDEN_FIELDS as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            if ($field === 'budget' && (float) ($data['budget'] ?? 0) <= 0) {
                continue;
            }
            if ($field === 'budget') {
                return array('error' => true, 'message' => 'Бюджет не используется для предквалификации');
            }
            if (!empty($data[$field])) {
                $messages = array(
                    'retendering_enabled' => 'Переторжка недоступна для предквалификации',
                    'itemized_enabled' => 'Попозиционная закупка недоступна для предквалификации',
                );
                return array('error' => true, 'message' => $messages[$field] ?? 'Поле недоступно для предквалификации');
            }
        }
        return null;
    }

    private function resolveTypeId(array $data): int
    {
        if (array_key_exists('type', $data)) {
            return (int) $data['type'];
        }
        return (int) ($this->data['type'] ?? 0);
    }

    private function assertMvpTypeAllowed(string $type_code, array $data, int $type_id): ?array
    {
        if (in_array($type_code, self::MVP_TYPE_CODES, true)) {
            return null;
        }

        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Тип процедуры пока недоступен');
        }

        if (array_key_exists('type', $data) && (int) ($this->data['type'] ?? 0) !== $type_id) {
            return array('error' => true, 'message' => 'Смена типа процедуры на этот вариант пока недоступна');
        }

        return null;
    }

    private function typeForbidsRetenderingAndItemized(string $type_code): bool
    {
        return in_array($type_code, array('quick_purchase', 'prequalification', 'single_supplier', 'auction'), true);
    }

    private function enforceOptionsForTypeCode(string $type_code, array &$data): ?array
    {
        switch ($type_code) {
            case 'quick_purchase':
            case 'prequalification':
            case 'single_supplier':
            case 'price_request':
            case 'proposal_request':
            case 'auction':
                if ($this->typeForbidsRetenderingAndItemized($type_code)) {
                    $flag_error = $this->rejectForbiddenOptionFlags($data);
                    if ($flag_error !== null) {
                        return $flag_error;
                    }
                    $this->clearForbiddenOptionFlagsIfTouched($data);
                }
                break;

            default:
                return array('error' => true, 'message' => 'Неверный тип процедуры');
        }

        if ($type_code === 'single_supplier') {
            $company_id = (int) ($data['single_supplier_company_id'] ?? $this->data['single_supplier_company_id'] ?? 0);
            $reason = trim((string) ($data['single_supplier_reason'] ?? $this->data['single_supplier_reason'] ?? ''));
            if ($company_id <= 0) {
                return array('error' => true, 'message' => 'Укажите поставщика для закупки у единственного поставщика');
            }
            if ($reason === '') {
                return array('error' => true, 'message' => 'Укажите обоснование закупки у единственного поставщика');
            }
        }

        return null;
    }

    private function rejectForbiddenOptionFlags(array $data): ?array
    {
        if (array_key_exists('retendering_enabled', $data) && !empty($data['retendering_enabled'])) {
            return array('error' => true, 'message' => 'Переторжка недоступна для выбранного типа процедуры');
        }
        if (array_key_exists('itemized_enabled', $data) && !empty($data['itemized_enabled'])) {
            return array('error' => true, 'message' => 'Попозиционная закупка недоступна для выбранного типа процедуры');
        }
        return null;
    }

    private function clearForbiddenOptionFlagsIfTouched(array &$data): void
    {
        if (!array_key_exists('type', $data)
            && !array_key_exists('retendering_enabled', $data)
            && !array_key_exists('itemized_enabled', $data)) {
            return;
        }
        $data['retendering_enabled'] = 0;
        $data['itemized_enabled'] = 0;
    }

    public function replaceClassifiers(array $rows, ?int $organizer_company_id = null): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Сначала сохраните тендер');
        }

        if ($organizer_company_id !== null && (int) ($this->data['organizer_company_id'] ?? 0) !== (int) $organizer_company_id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }

        $classifier_model = new pb2bTenderClassifierModel();
        $classifier_model->deleteByField('tender_id', $this->id);

        $saved = 0;
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $link = new pb2bTenderClassifier();
            $save_result = $link->save(array(
                'tender_id' => $this->id,
                'classifier_type' => (int) ($row['classifier_type'] ?? 0),
                'classifier_id' => (int) ($row['classifier_id'] ?? 0),
            ));
            if (!empty($save_result['error'])) {
                return $save_result;
            }
            $saved++;
        }

        return array(
            'error' => false,
            'message' => 'Классификаторы сохранены',
            'count' => $saved,
        );
    }

    public function replaceInvitations(array $supplier_company_ids, ?int $organizer_company_id = null): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Сначала сохраните тендер');
        }

        if ($organizer_company_id !== null && (int) ($this->data['organizer_company_id'] ?? 0) !== (int) $organizer_company_id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }

        $normalized = $this->normalizeSupplierCompanyIds($supplier_company_ids);
        if (!empty($normalized['error'])) {
            return $normalized;
        }
        $ids = (array) ($normalized['ids'] ?? array());

        try {
            $model = new pb2bInvitationModel();
            $model->deleteByField('tender_id', $this->id);
            $contact_id = (int) wa()->getUser()->getId();
            $saved = 0;
            foreach ($ids as $supplier_id) {
                $model->insert(array(
                    'tender_id' => (int) $this->id,
                    'supplier_company_id' => $supplier_id,
                    'invited_by_contact_id' => $contact_id > 0 ? $contact_id : null,
                    'status' => 'invited',
                ));
                $saved++;
            }

            return array(
                'error' => false,
                'message' => 'Приглашения сохранены',
                'count' => $saved,
            );
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица приглашений недоступна. Обратитесь к администратору',
            );
        }
    }

    public function replaceCriteria(array $rows, ?int $organizer_company_id = null): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Сначала сохраните тендер');
        }

        if ($organizer_company_id !== null && (int) ($this->data['organizer_company_id'] ?? 0) !== (int) $organizer_company_id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }

        try {
            $model = new pb2bCriterionModel();
            $model->deleteByField('tender_id', $this->id);
            $saved = 0;
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $name = trim((string) ($row['name'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $type = trim((string) ($row['type'] ?? 'non_price'));
                if ($type === '') {
                    $type = 'non_price';
                }
                $model->insert(array(
                    'tender_id' => (int) $this->id,
                    'type' => $type,
                    'name' => $name,
                    'weight' => array_key_exists('weight', $row) && $row['weight'] !== '' && $row['weight'] !== null
                        ? (float) $row['weight']
                        : null,
                    'is_mandatory' => !empty($row['is_mandatory']) ? 1 : 0,
                ));
                $saved++;
            }

            if ($saved < 1) {
                $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
                $type_code = (string) ($types_by_id[(int) ($this->data['type'] ?? 0)]['code'] ?? '');
                if ($type_code === 'price_request') {
                    return array('error' => true, 'message' => 'Добавьте хотя бы один критерий оценки');
                }
            }

            return array(
                'error' => false,
                'message' => 'Критерии сохранены',
                'count' => $saved,
            );
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица критериев недоступна. Обратитесь к администратору',
            );
        }
    }

    public function getClassifiers(): array
    {
        if (empty($this->id)) {
            return array();
        }
        return (new pb2bTenderClassifierCollection())->getByTenderId((int) $this->id);
    }

    public function transitionTo(int $to_status_id, ?string $reason = null, ?waContact $actor = null): array
    {
        return (new pb2bTenderStatusService())->transition($this, $to_status_id, $reason, $actor);
    }

    public function publish(?string $reason = null, ?waContact $actor = null): array
    {
        return (new pb2bTenderStatusService())->publish($this, $reason, $actor);
    }

    public function saveWizardStep(string $step, array $data, int $organizer_company_id): array
    {
        unset($data['status'], $data['_status_via_service'], $data['organizer_company_id']);

        $invitations_payload = null;
        if (array_key_exists('invitations', $data)) {
            $invitations_payload = (array) $data['invitations'];
            unset($data['invitations']);
        }
        $criteria_payload = null;
        if (array_key_exists('criteria', $data)) {
            $criteria_payload = (array) $data['criteria'];
            unset($data['criteria']);
        }

        $tender_id = (int) ($data['id'] ?? 0);
        $responsible_contact_id = (int) ($data['responsible_contact_id'] ?? 0);
        unset($data['id']);
        $data = $this->filterDataForWizardStep($step, $data);

        $data['organizer_company_id'] = $organizer_company_id;
        if ($responsible_contact_id > 0) {
            $data['responsible_contact_id'] = $responsible_contact_id;
        } elseif (empty($data['responsible_contact_id'])) {
            $contact_id = (int) wa()->getUser()->getId();
            if ($contact_id > 0) {
                $data['responsible_contact_id'] = $contact_id;
            }
        }

        if (!$tender_id) {
            if (empty($data['type'])) {
                return array('error' => true, 'message' => 'Не указан тип процедуры');
            }
            if (empty($data['number'])) {
                $data['number'] = 'DRAFT-'.date('YmdHis').'-'.$organizer_company_id;
            }
            if (empty(trim((string) ($data['title'] ?? '')))) {
                $data['title'] = 'Черновик';
            }
        }

        $tender = new pb2bTender($tender_id ?: null);
        if ($tender_id && (int) ($tender->data['organizer_company_id'] ?? 0) !== $organizer_company_id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }

        if ($tender_id > 0 && (int) ($tender->id ?? 0) > 0) {
            foreach (array('title', 'number', 'responsible_contact_id', 'type', 'organizer_company_id') as $preserve_field) {
                if (!array_key_exists($preserve_field, $data) && array_key_exists($preserve_field, $tender->data)) {
                    $data[$preserve_field] = $tender->data[$preserve_field];
                }
            }
        }

        $data['_wizard_step'] = $step;
        $save_result = $tender->save($data);
        if (!empty($save_result['error'])) {
            return $save_result;
        }

        $saved_tender_id = (int) $tender->id;
        if ($invitations_payload !== null && $saved_tender_id > 0) {
            $inv_result = $tender->replaceInvitations($invitations_payload, $organizer_company_id);
            if (!empty($inv_result['error'])) {
                return $inv_result;
            }
        }
        if ($criteria_payload !== null && $saved_tender_id > 0) {
            $crit_result = $tender->replaceCriteria($criteria_payload, $organizer_company_id);
            if (!empty($crit_result['error'])) {
                return $crit_result;
            }
        }

        return array(
            'error' => false,
            'message' => $save_result['message'] ?? 'Сохранено',
            'tender_id' => $saved_tender_id,
            'status' => (int) ($tender->data['status'] ?? 0),
        );
    }

    public function validateStep(string $step, array $data): array
    {
        $merged = array_merge($this->data, $data);
        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $type_code = (string) ($types_by_id[(int) ($merged['type'] ?? 0)]['code'] ?? '');

        switch ($step) {
            case 'privacy':
                if (!empty($merged['is_private']) && (int) $this->id > 0) {
                    $invitation_check = self::requireInvitationsForPrivate((int) $this->id, true);
                    if ($invitation_check !== null) {
                        return $invitation_check;
                    }
                }
                break;

            case 'basic':
                if ((int) ($merged['type'] ?? 0) <= 0) {
                    return array('error' => true, 'message' => 'Не указан тип процедуры');
                }
                if (trim((string) ($merged['title'] ?? '')) === '') {
                    return array('error' => true, 'message' => 'Укажите наименование');
                }
                if (trim((string) ($merged['number'] ?? '')) === '') {
                    return array('error' => true, 'message' => 'Укажите реестровый номер');
                }
                $dup = $this->findDuplicateNumber(
                    trim((string) $merged['number']),
                    (int) ($merged['organizer_company_id'] ?? 0)
                );
                if ($dup) {
                    return array('error' => true, 'message' => 'Тендер с таким номером уже существует');
                }
                break;

            case 'purchase_params':
                if ((int) ($merged['type'] ?? 0) <= 0) {
                    return array('error' => true, 'message' => 'Не указан тип процедуры');
                }
                if ($type_code === 'prequalification' && (int) ($merged['prequal_validity_months'] ?? 0) < 1) {
                    return array('error' => true, 'message' => 'Укажите срок действия предквалификации');
                }
                break;

            case 'lots':
                return array('error' => true, 'message' => 'Сохранение лотов будет доступно после подключения таблиц позиций');

            case 'invitation':
                if (!empty($merged['is_private']) && (int) $this->id > 0) {
                    $invitation_check = self::requireInvitationsForPrivate((int) $this->id, true);
                    if ($invitation_check !== null) {
                        return $invitation_check;
                    }
                }
                break;
        }

        return array('error' => false);
    }

    private function findDuplicateNumber(string $number, int $organizer_company_id): bool
    {
        if ($number === '' || $organizer_company_id <= 0) {
            return false;
        }
        $model = new pb2bTenderModel();
        $row = $model->getByField(array(
            'number' => $number,
            'organizer_company_id' => $organizer_company_id,
        ));
        if (empty($row['id'])) {
            return false;
        }
        return (int) $row['id'] !== (int) $this->id;
    }

    public static function countInvitationsForTender(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array('error' => false, 'count' => 0);
        }
        try {
            $model = new waModel();
            $row = $model->query(
                'SELECT COUNT(*) AS cnt FROM pb2b_invitation WHERE tender_id = ?',
                $tender_id
            )->fetchAssoc();
            return array('error' => false, 'count' => (int) ($row['cnt'] ?? 0));
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица приглашений недоступна. Обратитесь к администратору',
                'count' => null,
            );
        }
    }

    public static function requireInvitationsForPrivate(int $tender_id, bool $is_private): ?array
    {
        if (!$is_private || $tender_id <= 0) {
            return null;
        }
        $count_result = self::countInvitationsForTender($tender_id);
        if (!empty($count_result['error'])) {
            return array('error' => true, 'message' => (string) ($count_result['message'] ?? 'Ошибка проверки приглашений'));
        }
        if ((int) ($count_result['count'] ?? 0) < 1) {
            return array('error' => true, 'message' => 'Для закрытого тендера добавьте хотя бы одно приглашение');
        }
        return null;
    }

    public static function requirePriceRequestCriteria(int $tender_id): ?array
    {
        if ($tender_id <= 0) {
            return null;
        }
        try {
            $model = new waModel();
            $row = $model->query(
                'SELECT COUNT(*) AS cnt FROM pb2b_criterion WHERE tender_id = ?',
                $tender_id
            )->fetchAssoc();
            if ((int) ($row['cnt'] ?? 0) < 1) {
                return array('error' => true, 'message' => 'Добавьте хотя бы один критерий оценки для запроса цен');
            }
            return null;
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица критериев недоступна. Обратитесь к администратору',
            );
        }
    }

    public static function getInvitationsForTender(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array();
        }
        try {
            $model = new pb2bInvitationModel();
            $rows = $model->getByField('tender_id', $tender_id, true);
            return is_array($rows) ? $rows : array();
        } catch (Exception $e) {
            return array();
        }
    }

    public static function getCriteriaForTender(int $tender_id): array
    {
        if ($tender_id <= 0) {
            return array();
        }
        try {
            $model = new pb2bCriterionModel();
            $rows = $model->getByField('tender_id', $tender_id, true);
            return is_array($rows) ? $rows : array();
        } catch (Exception $e) {
            return array();
        }
    }

    private function normalizeSupplierCompanyIds(array $supplier_company_ids): array
    {
        $ids = array();
        foreach ($supplier_company_ids as $item) {
            if (is_array($item)) {
                $id = (int) ($item['supplier_company_id'] ?? $item['id'] ?? 0);
            } else {
                $id = (int) $item;
            }
            if ($id > 0) {
                $ids[$id] = $id;
            }
        }
        $ids = array_values($ids);

        if (!$ids) {
            return array('error' => false, 'ids' => array());
        }

        $company_model = new pb2bCompanyModel();
        foreach ($ids as $supplier_id) {
            $row = $company_model->getById($supplier_id);
            if (empty($row['id'])) {
                return array('error' => true, 'message' => 'Компания-поставщик не найдена');
            }
            if (empty($row['supplier'])) {
                return array('error' => true, 'message' => 'В приглашения можно добавлять только компании-поставщиков');
            }
        }

        return array('error' => false, 'ids' => $ids);
    }

    private function filterDataForWizardStep(string $step, array $data): array
    {
        $allowed = self::WIZARD_STEP_FIELDS[$step] ?? null;
        if ($allowed === null) {
            return $data;
        }
        $filtered = array();
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $filtered[$field] = $data[$field];
            }
        }
        return $filtered;
    }
}
