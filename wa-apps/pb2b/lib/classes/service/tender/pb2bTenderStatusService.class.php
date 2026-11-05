<?php

class pb2bTenderStatusService
{
    private pb2bTenderStateLogModel $stateLogModel;

    public function __construct()
    {
        $this->stateLogModel = new pb2bTenderStateLogModel();
    }

    public function canTransition(pb2bTender $tender, int $to_status_id, ?waContact $actor = null): array
    {
        $tender_id = (int) ($tender['id'] ?? 0);
        if ($tender_id <= 0) {
            return array('error' => true, 'message' => 'Тендер не найден');
        }

        if (!empty($tender->data['is_deleted'])) {
            return array('error' => true, 'message' => 'Нельзя менять статус удалённого тендера');
        }

        $from_status_id = (int) ($tender->data['status'] ?? 0);
        if ($from_status_id === $to_status_id) {
            return array(
                'error' => true,
                'message' => 'Тендер уже в этом статусе',
                'http_status' => pb2bHttpStatus::CONFLICT,
            );
        }

        if (!$this->isAllowedTransition($from_status_id, $to_status_id)) {
            return array(
                'error' => true,
                'message' => 'Переход между этими статусами недоступен',
                'http_status' => pb2bHttpStatus::CONFLICT,
            );
        }

        $publish_check = $this->validatePublishTargets($tender, $from_status_id, $to_status_id);
        if ($publish_check !== null) {
            return $publish_check;
        }

        return array('error' => false);
    }

    public function transition(pb2bTender $tender, int $to_status_id, ?string $reason = null, ?waContact $actor = null): array
    {
        $check = $this->canTransition($tender, $to_status_id, $actor);
        if (!empty($check['error'])) {
            return $check;
        }

        $from_status_id = (int) ($tender->data['status'] ?? 0);
        $actor_id = $this->resolveActorContactId($actor);

        $save_data = $tender->data;
        unset($save_data['create_datetime'], $save_data['update_datetime']);
        $save_data['status'] = $to_status_id;
        $save_data['_status_via_service'] = 1;

        $statuses_by_id = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $to_code = (string) ($statuses_by_id[$to_status_id]['code'] ?? '');
        if ($to_code === pb2bTenderStatus::PUBLISHED->value && empty($save_data['published_at'])) {
            $save_data['published_at'] = date('Y-m-d H:i:s');
        }

        $save_result = $tender->save($save_data);
        if (!empty($save_result['error'])) {
            return $save_result;
        }

        $this->stateLogModel->insert(array(
            'tender_id' => (int) ($tender['id'] ?? 0),
            'from_status' => $from_status_id ?: null,
            'to_status' => $to_status_id,
            'actor_contact_id' => $actor_id ?: null,
            'reason' => $reason,
            'at_dt' => date('Y-m-d H:i:s'),
        ));

        return array(
            'error' => false,
            'message' => 'Статус тендера изменён',
            'from_status' => $from_status_id,
            'to_status' => $to_status_id,
            'item' => $save_result['item'] ?? array(),
        );
    }

    public function resolvePublishStatusId(pb2bTender $tender): array
    {
        $statuses_by_id = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $current_id = (int) ($tender->data['status'] ?? 0);
        $current_code = (string) ($statuses_by_id[$current_id]['code'] ?? '');

        if ($current_code === pb2bTenderStatus::PENDING_APPROVAL->value) {
            return array(
                'error' => false,
                'status_id' => pb2bTenderStatus::PUBLISHED->id(),
            );
        }

        if ($current_code === pb2bTenderStatus::DRAFT->value) {
            if (!empty($tender->data['approval_required'])) {
                return array(
                    'error' => false,
                    'status_id' => pb2bTenderStatus::PENDING_APPROVAL->id(),
                );
            }
            return array(
                'error' => false,
                'status_id' => pb2bTenderStatus::PUBLISHED->id(),
            );
        }

        return array(
            'error' => true,
            'message' => 'Публикация недоступна из текущего статуса',
            'http_status' => pb2bHttpStatus::CONFLICT,
        );
    }

    public function publish(pb2bTender $tender, ?string $reason = null, ?waContact $actor = null): array
    {
        $resolved = $this->resolvePublishStatusId($tender);
        if (!empty($resolved['error'])) {
            return $resolved;
        }

        $result = $this->transition($tender, (int) $resolved['status_id'], $reason, $actor);
        if (!empty($result['error'])) {
            return $result;
        }

        $reception = $this->ensureReceptionOpen($tender, $reason, $actor);
        if (!empty($reception['error'])) {
            return $reception;
        }
        if (empty($reception['skipped'])) {
            return $reception;
        }

        return $result;
    }

    /**
     * ЗЦ после публикации: published → accepting_applications, если start_at уже наступил.
     * TODO: Перенести автоматический вызов из методов чтения в CLI-команду открытия приёма.
     *
     * @return array{error:bool,skipped?:bool,message?:string,from_status?:int,to_status?:int,item?:array}
     */
    public function ensureReceptionOpen(pb2bTender $tender, ?string $reason = null, ?waContact $actor = null): array
    {
        if ($this->typeCode($tender) !== pb2bTenderType::PRICE_REQUEST->value) {
            return array('error' => false, 'skipped' => true);
        }
        if ($this->statusCode($tender) !== pb2bTenderStatus::PUBLISHED->value) {
            return array('error' => false, 'skipped' => true);
        }

        $start_at = trim((string) ($tender->data['start_at'] ?? ''));
        if ($start_at !== '' && strpos($start_at, '0000-00-00') !== 0 && strtotime($start_at) > time()) {
            return array('error' => false, 'skipped' => true);
        }

        $accepting_applications_id = pb2bTenderStatus::ACCEPTING_APPLICATIONS->id();
        if ($accepting_applications_id <= 0) {
            return array('error' => true, 'message' => 'Статус приёма заявок не настроен');
        }

        return $this->transition(
            $tender,
            $accepting_applications_id,
            $reason ?: 'Автоматическое открытие приёма заявок',
            $actor
        );
    }

    private function typeCode(pb2bTender $tender): string
    {
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');

        return (string) ($types[(int) ($tender->data['type'] ?? 0)]['code'] ?? '');
    }

    private function statusCode(pb2bTender $tender): string
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');

        return (string) ($statuses[(int) ($tender->data['status'] ?? 0)]['code'] ?? '');
    }

    private function isAllowedTransition(int $from_status_id, int $to_status_id): bool
    {
        $matrix = (array) pb2bWaproHelper::getConfigOption('tender_status_transitions');
        if (empty($matrix)) {
            return false;
        }

        $statuses_by_id = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $from_row = $statuses_by_id[$from_status_id] ?? null;
        $to_row = $statuses_by_id[$to_status_id] ?? null;
        if (empty($from_row['code']) || empty($to_row['code'])) {
            return false;
        }

        $allowed_codes = (array) ($matrix[$from_row['code']] ?? array());
        return in_array($to_row['code'], $allowed_codes, true);
    }

    private function validatePublishTargets(pb2bTender $tender, int $from_status_id, int $to_status_id): ?array
    {
        $statuses_by_id = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $from_code = (string) ($statuses_by_id[$from_status_id]['code'] ?? '');
        $to_code = (string) ($statuses_by_id[$to_status_id]['code'] ?? '');

        $is_initial_publish = $from_code === pb2bTenderStatus::DRAFT->value
            && in_array($to_code, array(pb2bTenderStatus::PUBLISHED->value, pb2bTenderStatus::PENDING_APPROVAL->value), true);
        $is_approval_publish = $from_code === pb2bTenderStatus::PENDING_APPROVAL->value
            && $to_code === pb2bTenderStatus::PUBLISHED->value;
        if (!$is_initial_publish && !$is_approval_publish) {
            return null;
        }

        $data = $tender->data;
        $issues = array();

        foreach (array('number', 'type', 'organizer_company_id', 'responsible_contact_id') as $field) {
            if (empty($data[$field])) {
                $issues[] = array(
                    'step' => 'basic',
                    'field' => $field,
                    'message' => 'Заполните обязательные поля перед публикацией',
                );
                break;
            }
        }

        $title = trim((string) ($data['title'] ?? ''));
        if ($title === '') {
            $issues[] = array(
                'step' => 'basic',
                'field' => 'title',
                'message' => 'Укажите наименование извещения',
            );
        }

        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $type_code = (string) ($types_by_id[(int) ($data['type'] ?? 0)]['code'] ?? '');
        if ($type_code === pb2bTenderType::PREQUALIFICATION->value && (int) ($data['prequal_validity_months'] ?? 0) < 1) {
            $issues[] = array(
                'step' => 'purchase_params',
                'field' => 'prequal_validity_months',
                'message' => 'Укажите срок действия предквалификации',
            );
        }

        $end_at = trim((string) ($data['end_at'] ?? ''));
        if ($end_at === '' || strpos($end_at, '0000-00-00') === 0) {
            $issues[] = array(
                'step' => 'purchase_params',
                'field' => 'end_at',
                'message' => 'Укажите конечный срок подачи заявок',
            );
        } elseif ($tender->isReceptionDeadlinePassed()) {
            $issues[] = array(
                'step' => 'purchase_params',
                'field' => 'end_at',
                'message' => 'Конечный срок подачи заявок должен быть позже текущего времени',
            );
        }

        if (!empty($data['is_private']) && !$tender->getInvitations()) {
            $issues[] = array(
                'step' => 'privacy',
                'field' => 'invitations',
                'message' => 'Для закрытого тендера добавьте хотя бы одно приглашение',
            );
        }

        if ($type_code === pb2bTenderType::PRICE_REQUEST->value) {
            $has_ready_item = false;
            foreach ($tender->getItems() as $item) {
                if (trim((string) $item['name']) !== ''
                    && (float) $item['qty'] > 0
                    && trim((string) $item['unit']) !== ''
                    && trim((string) $item['delivery_place']) !== '') {
                    $has_ready_item = true;
                    break;
                }
            }
            if (!$has_ready_item) {
                $issues[] = array(
                    'step' => 'lots',
                    'field' => 'items',
                    'message' => 'Заполните хотя бы одну позицию, чтобы продолжить',
                );
            }
        }

        if (!$issues) {
            return null;
        }

        return array(
            'error' => true,
            'message' => 'Заполните обязательные поля перед публикацией',
            'issues' => $issues,
        );
    }

    private function resolveActorContactId(?waContact $actor): int
    {
        if ($actor && $actor->getId()) {
            return (int) $actor->getId();
        }
        $user = wa()->getUser();
        return $user ? (int) $user->getId() : 0;
    }
}
