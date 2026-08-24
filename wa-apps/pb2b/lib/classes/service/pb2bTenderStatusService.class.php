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
            return array('error' => true, 'message' => 'Тендер уже в этом статусе');
        }

        if (!$this->isAllowedTransition($from_status_id, $to_status_id)) {
            return array('error' => true, 'message' => 'Переход между этими статусами недоступен');
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
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'code');
        $statuses_by_id = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $current_id = (int) ($tender->data['status'] ?? 0);
        $current_code = (string) ($statuses_by_id[$current_id]['code'] ?? '');

        if ($current_code === 'na_soglasovanii') {
            return array(
                'error' => false,
                'status_id' => (int) ($statuses['opublikovan']['id'] ?? 3),
            );
        }

        if ($current_code === 'draft') {
            if (!empty($tender->data['approval_required'])) {
                return array(
                    'error' => false,
                    'status_id' => (int) ($statuses['na_soglasovanii']['id'] ?? 2),
                );
            }
            return array(
                'error' => false,
                'status_id' => (int) ($statuses['opublikovan']['id'] ?? 3),
            );
        }

        return array('error' => true, 'message' => 'Публикация недоступна из текущего статуса');
    }

    public function publish(pb2bTender $tender, ?string $reason = null, ?waContact $actor = null): array
    {
        $resolved = $this->resolvePublishStatusId($tender);
        if (!empty($resolved['error'])) {
            return $resolved;
        }

        return $this->transition($tender, (int) $resolved['status_id'], $reason, $actor);
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

        $is_initial_publish = $from_code === 'draft'
            && in_array($to_code, array('opublikovan', 'na_soglasovanii'), true);
        $is_approval_publish = $from_code === 'na_soglasovanii' && $to_code === 'opublikovan';
        if (!$is_initial_publish && !$is_approval_publish) {
            return null;
        }

        $data = $tender->data;
        foreach (array('number', 'title', 'type', 'organizer_company_id', 'responsible_contact_id') as $field) {
            if (empty($data[$field])) {
                return array('error' => true, 'message' => 'Заполните обязательные поля перед публикацией');
            }
        }

        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $type_code = (string) ($types_by_id[(int) $data['type']]['code'] ?? '');
        if (!in_array($type_code, array('prequalification', 'price_request'), true)) {
            return array('error' => true, 'message' => 'Публикация для этого типа процедуры пока недоступна');
        }
        if ($type_code === 'prequalification' && (int) ($data['prequal_validity_months'] ?? 0) < 1) {
            return array('error' => true, 'message' => 'Укажите срок действия предквалификации');
        }

        if (!empty($data['is_private'])) {
            return pb2bTender::requireInvitationsForPrivate((int) ($tender['id'] ?? 0), true);
        }

        if ($type_code === 'price_request') {
            return pb2bTender::requirePriceRequestCriteria((int) ($tender['id'] ?? 0));
        }

        return null;
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
