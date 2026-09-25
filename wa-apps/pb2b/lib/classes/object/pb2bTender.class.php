<?php

class pb2bTender extends pb2bWaproObject
{
    private const MVP_TYPE_CODES = array('prequalification', 'price_request');

    private const PREQUAL_FORBIDDEN_FIELDS = array(
        'retendering_enabled', 'itemized_enabled', 'budget',
    );

    public static function getMvpTypeCodes(): array
    {
        return self::MVP_TYPE_CODES;
    }

    public static function isMvpTypeCode(string $type_code): bool
    {
        return in_array($type_code, self::MVP_TYPE_CODES, true);
    }

    /**
     * Список способов для модалки создания (порядок и тексты из config tender_types).
     */
    public static function getCreateModalMethods(): array
    {
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types');
        $methods = array();

        foreach ($types as $row) {
            if (!is_array($row) || empty($row['code'])) {
                continue;
            }
            if (empty($row['show_in_create_modal'])) {
                continue;
            }
            $code = (string) $row['code'];
            $methods[] = array(
                'id' => (int) ($row['id'] ?? 0),
                'code' => $code,
                'label' => (string) ($row['modal_name'] ?? $row['name'] ?? $code),
                'description' => (string) ($row['description'] ?? ''),
                'create_title' => (string) ($row['create_title'] ?? ($row['name'] ?? $code)),
                'available' => self::isMvpTypeCode($code),
                'sort' => (int) ($row['create_modal_sort'] ?? 100),
            );
        }

        usort($methods, static function (array $a, array $b): int {
            return ($a['sort'] <=> $b['sort']) ?: ($a['id'] <=> $b['id']);
        });

        foreach ($methods as &$method) {
            unset($method['sort']);
        }
        unset($method);

        return $methods;
    }

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
        $status_via_service = !empty($data['_status_via_service']);
        unset($data['_status_via_service'], $data['_wizard_step']);
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

        $type_check = $this->applyTypeRules($data);
        if ($type_check !== null) {
            return $type_check;
        }

        $tender_fields = pb2bWaproHelper::getFields('tender');
        if (!is_array($tender_fields)) {
            $tender_fields = array();
        }
        foreach (array('start_at', 'end_at', 'opening_at', 'docs_end_at', 'published_at') as $dt_field) {
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

    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);
        if (empty($result['error']) && !empty($result['new'])) {
            $result['dispatch_url'] = '#/tender/edit/id='.$this->id;
        }
    }

    private function applyTypeRules(array &$data): ?array
    {
        $type_id = $this->resolveTypeId($data);
        if ($type_id <= 0) {
            if (empty($this->id)) {
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
        if (self::isMvpTypeCode($type_code)) {
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
                $insert = array(
                    'tender_id' => (int) $this->id,
                    'type' => $type,
                    'name' => $name,
                    'weight' => array_key_exists('weight', $row) && $row['weight'] !== '' && $row['weight'] !== null
                        ? (float) $row['weight']
                        : null,
                    'is_mandatory' => !empty($row['is_mandatory']) ? 1 : 0,
                );
                if (array_key_exists('description', $row)) {
                    $insert['description'] = trim((string) $row['description']);
                }
                $model->insert($insert);
                $saved++;
            }

            // Пустой набор допустим в черновике; обязательность для ЗЦ — на publish.
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

    public function replaceItems(array $rows, ?int $organizer_company_id = null): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Сначала сохраните тендер');
        }

        if ($organizer_company_id !== null && (int) ($this->data['organizer_company_id'] ?? 0) !== (int) $organizer_company_id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }

        try {
            $model = new pb2bTenderItemModel();
            $model->deleteByField('tender_id', $this->id);
            $saved = 0;
            $sort = 0;
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $name = trim((string) ($row['name'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $qty = (float) ($row['qty'] ?? $row['quantity'] ?? 0);
                if ($qty <= 0) {
                    $qty = 1;
                }
                $file_link_id = (int) ($row['file_link_id'] ?? 0);
                $insert = array(
                    'tender_id' => (int) $this->id,
                    'name' => $name,
                    'qty' => $qty,
                    'unit' => trim((string) ($row['unit'] ?? '')) ?: null,
                    'max_price_no_vat' => array_key_exists('max_price_no_vat', $row) && $row['max_price_no_vat'] !== '' && $row['max_price_no_vat'] !== null
                        ? (float) $row['max_price_no_vat']
                        : (array_key_exists('maxPriceNoVat', $row) && $row['maxPriceNoVat'] !== '' && $row['maxPriceNoVat'] !== null
                            ? (float) $row['maxPriceNoVat']
                            : null),
                    'vat_rate' => trim((string) ($row['vat_rate'] ?? $row['vatRate'] ?? '')) ?: null,
                    'delivery_place' => trim((string) ($row['delivery_place'] ?? $row['deliveryPlace'] ?? '')) ?: null,
                    'comment' => trim((string) ($row['comment'] ?? '')) ?: null,
                    'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                    'sort' => array_key_exists('sort', $row) ? (int) $row['sort'] : $sort,
                );
                $model->insert($insert);
                $saved++;
                $sort++;
            }

            return array(
                'error' => false,
                'message' => 'Позиции сохранены',
                'count' => $saved,
            );
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица позиций недоступна. Обратитесь к администратору',
            );
        }
    }

    public function replaceDocuments(array $rows, ?int $organizer_company_id = null): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Сначала сохраните тендер');
        }

        if ($organizer_company_id !== null && (int) ($this->data['organizer_company_id'] ?? 0) !== (int) $organizer_company_id) {
            return array('error' => true, 'message' => 'Нет доступа к этому тендеру');
        }

        try {
            $model = new pb2bTenderDocumentModel();
            $model->deleteByField('tender_id', $this->id);
            $saved = 0;
            $sort = 0;
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $kind = trim((string) ($row['kind'] ?? ''));
                if (!pb2bTenderDocument::isAllowedKind($kind)) {
                    continue;
                }
                $name = trim((string) ($row['name'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $file_link_id = (int) ($row['file_link_id'] ?? 0);
                $model->insert(array(
                    'tender_id' => (int) $this->id,
                    'kind' => $kind,
                    'name' => $name,
                    'description' => trim((string) ($row['description'] ?? '')) ?: null,
                    'is_required' => !empty($row['is_required']) || !empty($row['isRequired']) ? 1 : 0,
                    'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                    'sort' => array_key_exists('sort', $row) ? (int) $row['sort'] : $sort,
                ));
                $saved++;
                $sort++;
            }

            return array(
                'error' => false,
                'message' => 'Документы сохранены',
                'count' => $saved,
            );
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица документов тендера недоступна. Обратитесь к администратору',
            );
        }
    }

    public function getItems(): array
    {
        if (empty($this->id)) {
            return array();
        }
        try {
            $model = new pb2bTenderItemModel();
            $rows = $model->getByField('tender_id', (int) $this->id, true);
            if (!is_array($rows)) {
                return array();
            }
            usort($rows, static function ($a, $b) {
                return ((int) ($a['sort'] ?? 0)) <=> ((int) ($b['sort'] ?? 0));
            });
            return array_values($rows);
        } catch (Exception $e) {
            return array();
        }
    }

    /** Позиции для карточки: + имя файла по file_link_id. */
    public function getItemsForView(): array
    {
        return self::attachFileMetaToRows($this->getItems());
    }

    public function getDocuments(?string $kind = null): array
    {
        if (empty($this->id)) {
            return array();
        }
        try {
            $model = new pb2bTenderDocumentModel();
            $rows = $model->getByField('tender_id', (int) $this->id, true);
            if (!is_array($rows)) {
                return array();
            }
            $out = array_values($rows);
            if ($kind !== null && $kind !== '') {
                $out = array_values(array_filter($out, static function ($row) use ($kind) {
                    return (string) ($row['kind'] ?? '') === $kind;
                }));
            }
            usort($out, static function ($a, $b) {
                return ((int) ($a['sort'] ?? 0)) <=> ((int) ($b['sort'] ?? 0));
            });
            return $out;
        } catch (Exception $e) {
            return array();
        }
    }

    /** Документы для карточки: + имя файла по file_link_id. */
    public function getDocumentsForView(?string $kind = null): array
    {
        return self::attachFileMetaToRows($this->getDocuments($kind));
    }

    /** Сумма qty × max_price_no_vat по позициям (НМЦ из лотов). */
    public function getItemsMaxTotal(): float
    {
        $total = 0.0;
        foreach ($this->getItems() as $row) {
            $qty = (float) ($row['qty'] ?? 0);
            $price = (float) ($row['max_price_no_vat'] ?? 0);
            if ($qty > 0 && $price > 0) {
                $total += $qty * $price;
            } elseif ($price > 0) {
                $total += $price;
            }
        }
        return $total;
    }

    /**
     * @param list<array<string,mixed>> $rows
     * @return list<array<string,mixed>>
     */
    private static function attachFileMetaToRows(array $rows): array
    {
        foreach ($rows as &$row) {
            if (!is_array($row)) {
                continue;
            }
            $row['file_name'] = '';
            $file_link_id = (int) ($row['file_link_id'] ?? 0);
            if ($file_link_id <= 0) {
                continue;
            }
            try {
                $link = new pb2bFileLink($file_link_id);
                if (!empty($link->id)) {
                    $row['file_name'] = (string) ($link->data['filename'] ?? '');
                }
            } catch (Exception $e) {
                // файл недоступен — оставляем пустое имя
            }
        }
        unset($row);

        return $rows;
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

    public function findDuplicateNumber(string $number, int $organizer_company_id): bool
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

    public static function requirePriceRequestItems(int $tender_id): ?array
    {
        if ($tender_id <= 0) {
            return null;
        }
        try {
            $model = new waModel();
            $row = $model->query(
                'SELECT COUNT(*) AS cnt FROM pb2b_tender_item WHERE tender_id = ?',
                $tender_id
            )->fetchAssoc();
            if ((int) ($row['cnt'] ?? 0) < 1) {
                return array('error' => true, 'message' => 'Добавьте хотя бы одну позицию для запроса цен');
            }
            return null;
        } catch (Exception $e) {
            return array(
                'error' => true,
                'message' => 'Таблица позиций недоступна. Обратитесь к администратору',
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
            if (!is_array($rows)) {
                return array();
            }
            $out = array();
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $company_id = (int) ($row['supplier_company_id'] ?? 0);
                $row['company_name'] = '';
                if ($company_id > 0) {
                    try {
                        $company = new pb2bCompany($company_id);
                        if (!empty($company->id)) {
                            $row['company_name'] = $company->getFullName();
                        }
                    } catch (Exception $e) {
                        // компания недоступна
                    }
                }
                $out[] = $row;
            }
            return $out;
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
}
