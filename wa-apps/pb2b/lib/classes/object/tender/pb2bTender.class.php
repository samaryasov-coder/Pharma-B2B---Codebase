<?php

class pb2bTender extends pb2bWaproObject
{

    protected function preSave(array &$data): array
    {
        $status_via_service = !empty($data['_status_via_service']);
        $winner_via_service = !empty($data['_winner_via_service']);
        unset($data['_status_via_service'], $data['_wizard_step'], $data['_winner_via_service']);
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
            $none_id = self::winnerSelectionModeCodeToId('none');
            if ($none_id <= 0) {
                $none_id = 1;
            }
            if (!array_key_exists('winner_selection_mode', $data) || (int) $data['winner_selection_mode'] <= 0) {
                $data['winner_selection_mode'] = $none_id;
            }
            if (!array_key_exists('winner_selection_completed', $data)) {
                $data['winner_selection_completed'] = 0;
            }
            if (!array_key_exists('finished_without_winner', $data)) {
                $data['finished_without_winner'] = 0;
            }
            if (!array_key_exists('finished_without_winner_reason', $data)) {
                $data['finished_without_winner_reason'] = null;
            }
        } elseif (!$winner_via_service) {
            // Wizard/обычный save не должен затирать решение по победителю.
            foreach (array(
                'winner_selection_mode',
                'winner_selection_completed',
                'finished_without_winner',
                'finished_without_winner_reason',
            ) as $winner_field) {
                if (array_key_exists($winner_field, $this->data)) {
                    $data[$winner_field] = $this->data[$winner_field];
                }
            }
        } else {
            if (array_key_exists('winner_selection_mode', $data)) {
                $mode_id = (int) $data['winner_selection_mode'];
                $by_id = (array) pb2bWaproHelper::getConfigOption('tender_winner_selection_modes', 'id');
                if ($mode_id <= 0 || empty($by_id[$mode_id])) {
                    return array('error' => true, 'message' => 'Неверный режим выбора победителя');
                }
                $data['winner_selection_mode'] = $mode_id;
            }
            if (array_key_exists('winner_selection_completed', $data)) {
                $data['winner_selection_completed'] = !empty($data['winner_selection_completed']) ? 1 : 0;
            }
            if (array_key_exists('finished_without_winner', $data)) {
                $data['finished_without_winner'] = !empty($data['finished_without_winner']) ? 1 : 0;
            }
            if (array_key_exists('finished_without_winner_reason', $data)) {
                $reason = trim((string) ($data['finished_without_winner_reason'] ?? ''));
                $data['finished_without_winner_reason'] = $reason !== '' ? $reason : null;
            }
        }
        if (array_key_exists('submission_form', $data) && $data['submission_form'] === '') {
            $data['submission_form'] = null;
        }
        if (empty($this->id)) {
            $draft_id = pb2bTenderStatus::DRAFT->id();
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
            $value = $data[$dt_field];
            if (is_scalar($value) && trim((string) $value) === '') {
                $value = null;
            }
            list($ok, $mysql_dt) = pb2bWaproHelper::normalizeDatetimeValueForMysql($value);
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


    /**
     * Строка tender_types по code или null.
     */
    public static function getTypeConfigByCode(string $type_code): ?array
    {
        $type_code = trim($type_code);
        if ($type_code === '') {
            return null;
        }
        $by_code = (array) pb2bWaproHelper::getConfigOption('tender_types', 'code');
        $row = $by_code[$type_code] ?? null;
        return is_array($row) ? $row : null;
    }

    /**
     * Шаги мастера создания/редактирования для типа (key + label, порядок из config).
     *
     * @return list<array{key: string, label: string}>
     */
    public static function getCreateSteps(string $type_code): array
    {
        $row = self::getTypeConfigByCode($type_code);
        if ($row === null) {
            return array();
        }

        $raw = $row['create_steps'] ?? null;
        if (!is_array($raw) || !$raw) {
            return array();
        }

        $steps = array();
        foreach ($raw as $step) {
            if (!is_array($step)) {
                continue;
            }
            $key = trim((string) ($step['key'] ?? ''));
            if ($key === '') {
                continue;
            }
            $label = trim((string) ($step['label'] ?? ''));
            $steps[] = array(
                'key' => $key,
                'label' => $label !== '' ? $label : $key,
            );
        }

        return $steps;
    }

    /**
     * Заголовок мастера создания: из config или «Редактирование …» при $edit.
     */
    public static function getCreateTitle(string $type_code, bool $edit = false): string
    {
        $row = self::getTypeConfigByCode($type_code);
        $create_title = '';
        if ($row !== null) {
            $create_title = trim((string) ($row['create_title'] ?? ''));
            if ($create_title === '') {
                $create_title = trim((string) ($row['name'] ?? $type_code));
            }
        }
        if ($create_title === '') {
            $create_title = $type_code !== '' ? $type_code : 'процедуры';
        }

        if (!$edit) {
            return $create_title;
        }

        if (mb_strpos($create_title, 'Создание') === 0) {
            return 'Редактирование'.mb_substr($create_title, mb_strlen('Создание'));
        }

        return 'Редактирование: '.$create_title;
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

    public function canEdit(): bool
    {
        if (!(int) $this->id || !empty($this->data['is_deleted']))  return false;

        $status = pb2bTenderStatus::tryFromId((int) ($this->data['status'] ?? 0));
        $type = pb2bTenderType::tryFromId((int) ($this->data['type'] ?? 0));
        if ($status !== pb2bTenderStatus::DRAFT && !($type === pb2bTenderType::PRICE_REQUEST && $status === pb2bTenderStatus::ACCEPTING_APPLICATIONS)) {
            return false;
        }
        if ($status !== pb2bTenderStatus::DRAFT && $this->isReceptionDeadlinePassed()) {
            return false;
        }

        $counts_by_tender = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds(array((int) $this->id));
        $counts = $counts_by_tender[(int) $this->id];

        return $counts[pb2bTenderApplication::STATUS_SUBMITTED] + $counts[pb2bTenderApplication::STATUS_WITHDRAWN] === 0;
    }

    // получение доп данных тендера
    public function getDocuments(?string $kind = null): array
    {
        return (new pb2bTenderDocumentCollection())->getByTenderId((int) $this->id, $kind);
    }

    public function getItems(): array
    {
        return (new pb2bTenderItemCollection())->getByTenderId((int) $this->id);
    }

    public function getClassifiers(): array
    {
        return (new pb2bTenderClassifierCollection())->getByTenderId((int) $this->id);
    }

    public function getInvitations(): array
    {
        return (new pb2bTenderInvitationCollection())->getByTenderId((int) $this->id);
    }

    //
    public function getCriteria(): array
    {
        return (new pb2bTenderCriterionCollection())->getByTenderId((int) $this->id);
    }






    private function applyTypeRules(array &$data): ?array
    {
        $type_id = array_key_exists('type', $data)
            ? (int) $data['type']
            : (int) ($this->data['type'] ?? 0);
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
        return $this->enforceOptionsForTypeCode($type_code, $data);
    }

    private function typeForbidsRetenderingAndItemized(string $type_code): bool
    {
        return in_array($type_code, array(
            pb2bTenderType::QUICK_PURCHASE->value,
            pb2bTenderType::SINGLE_SUPPLIER->value,
            pb2bTenderType::AUCTION->value,
        ), true);
    }

    private function enforceOptionsForTypeCode(string $type_code, array &$data): ?array
    {
        $type = pb2bTenderType::tryFrom($type_code);
        if ($type === null) {
            return array('error' => true, 'message' => 'Неверный тип процедуры');
        }

        switch ($type) {
            case pb2bTenderType::QUICK_PURCHASE:
            case pb2bTenderType::SINGLE_SUPPLIER:
            case pb2bTenderType::PRICE_REQUEST:
            case pb2bTenderType::PROPOSAL_REQUEST:
            case pb2bTenderType::AUCTION:
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

        if ($type === pb2bTenderType::SINGLE_SUPPLIER) {
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

    

    public function canSelectWinner(): bool
    {
        $status = pb2bTenderStatus::tryFromId((int) $this->data['status']);

        return empty($this->data['winner_selection_completed'])
            && in_array($status, array(
                pb2bTenderStatus::REVIEW_AND_ADMISSION,
                pb2bTenderStatus::EVALUATION,
            ), true);
    }

    public function canStartReview(?DateTimeInterface $now = null): bool
    {
        if (!(int) $this->id || !empty($this->data['is_deleted'])
            || pb2bTenderType::tryFromId((int) ($this->data['type'] ?? 0)) !== pb2bTenderType::PRICE_REQUEST) {
            return false;
        }

        $status = pb2bTenderStatus::tryFromId((int) ($this->data['status'] ?? 0));

        return $status === pb2bTenderStatus::OPENING_APPLICATIONS
            || ($status === pb2bTenderStatus::ACCEPTING_APPLICATIONS && $this->isReceptionDeadlinePassed($now));
    }

    public function isReviewStarted(): bool
    {
        $status = pb2bTenderStatus::tryFromId((int) ($this->data['status'] ?? 0));

        return in_array($status, [
            pb2bTenderStatus::REVIEW_AND_ADMISSION,
            pb2bTenderStatus::REBIDDING,
            pb2bTenderStatus::AUCTION,
            pb2bTenderStatus::EVALUATION,
            pb2bTenderStatus::SUMMARIZING_RESULTS,
            pb2bTenderStatus::CONTRACT_SIGNING,
            pb2bTenderStatus::ARCHIVED,
        ], true);
    }



    /**
     * Дедлайн приёма в Europe/Moscow: end_at в БД — «настенные» часы Москвы без TZ.
     * Сервер может быть в UTC; сравнивать через strtotime/time() нельзя.
     */
    public function isReceptionDeadlinePassed(?DateTimeInterface $now = null): bool
    {
        return self::isReceptionDatetimePassed(
            isset($this->data['end_at']) ? (string) $this->data['end_at'] : null,
            $now
        );
    }

    public static function isReceptionDatetimePassed(?string $datetime, ?DateTimeInterface $now = null): bool
    {
        $datetime = trim((string) $datetime);
        if ($datetime === '' || strpos($datetime, '0000-00-00') === 0) {
            return false;
        }

        list($valid, $normalized) = pb2bWaproHelper::normalizeDatetimeValueForMysql($datetime);
        if (!$valid || !$normalized) {
            return false;
        }

        $tz = new DateTimeZone('Europe/Moscow');
        $end = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $normalized, $tz);
        if (!$end) {
            return false;
        }

        if ($now instanceof DateTimeInterface) {
            $now_moscow = DateTimeImmutable::createFromInterface($now)->setTimezone($tz);
        } else {
            $now_moscow = new DateTimeImmutable('now', $tz);
        }

        return $end->getTimestamp() <= $now_moscow->getTimestamp();
    }



    // winnerSelection

    /** Codes словаря tender_winner_selection_modes (в БД — id). */
    public static function winnerSelectionModeIdToCode(int $id): string
    {
        if ($id <= 0) {
            return 'none';
        }
        $by_id = (array) pb2bWaproHelper::getConfigOption('tender_winner_selection_modes', 'id');
        $code = trim((string) ($by_id[$id]['code'] ?? ''));

        return $code !== '' ? $code : 'none';
    }

    /**
     * Code → id словаря. Неизвестный / пустой code → 0 (сервис отдаёт 400).
     */
    public static function winnerSelectionModeCodeToId(string $code): int
    {
        $code = trim($code);
        if ($code === '') {
            return 0;
        }
        $by_code = (array) pb2bWaproHelper::getConfigOption('tender_winner_selection_modes', 'code');
        if (empty($by_code[$code]['id'])) {
            return 0;
        }

        return (int) $by_code[$code]['id'];
    }

    public static function isAllowedWinnerSelectionModeCode(string $code): bool
    {
        return self::winnerSelectionModeCodeToId($code) > 0;
    }

    public function getWinnerSelectionModeId(): int
    {
        return (int) ($this->data['winner_selection_mode'] ?? 0);
    }

    public function getWinnerSelectionModeCode(): string
    {
        return self::winnerSelectionModeIdToCode($this->getWinnerSelectionModeId());
    }
}
