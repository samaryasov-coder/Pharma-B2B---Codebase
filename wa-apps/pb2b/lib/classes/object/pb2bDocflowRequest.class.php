<?php

class pb2bDocflowRequest extends pb2bWaproObject
{
    protected function preSave(array &$data): array
    {
        $result = parent::preSave($data);
        if (!empty($result['error'])) return $result;

        if (!empty($result['new']))
            $this->generateProcedureCode($data);

        return $result;
    }

    protected function preDelete(array &$data = array()): array
    {
        $result = parent::preDelete($data);
        if ($result['error']) return $result;

        pb2bDocflowRequestItem::deleteByRequest($this->id);
        pb2bDocflowRequestHistory::deleteByRequest($this->id);

        return $result;
    }

    private function hasStatus(array $allowed): bool
    {
        return in_array($this->getStatus(), $allowed, true);
    }

    private function getCompany(int $company_id): ?pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        return $company->id ? $company : null;
    }

    private function changeStatusAndCreateHistory(pb2bDocflowRequestStatus $new_status): void
    {
        $old_status = $this->getStatus();
        $this->save([
            'status' => $new_status->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);

        $history = new pb2bDocflowRequestHistory();
        $history->save([
            'request_id' => $this->id,
            'status_from' => $old_status->value,
            'status_to' => $new_status->value
        ]);
    }



    public function __construct(?int $id = null)
    {
        $this->model = new pb2bDocflowRequestModel();
        parent::__construct($id);
    }


    /**
     * Возвращает все элементы запроса
     *
     * @return pb2bDocflowRequestItem[]
     */
    public function getItems(): array
    {
        if (!$this->id) return [];

        return pb2bDocflowRequestItem::getByRequest($this->id);
    }


    /**
     * Возвращает компанию-инициатор (покупатель)
     */
    public function getReviewerCompany(): ?pb2bCompany
    {
        return $this->getCompany($this->data['reviewer_company_id'] ?? 0);
    }


    /**
     * Возвращает компанию-поставщик
     */
    public function getProviderCompany(): ?pb2bCompany
    {
        return $this->getCompany($this->data['provider_company_id'] ?? 0);
    }


    /**
     * Возвращает статус запроса
     */
    public function getStatus(): pb2bDocflowRequestStatus
    {
        return pb2bDocflowRequestStatus::from($this->data['status']);
    }


    /**
     * Проверяет, может ли инициатор отправить запрос
     */
    public function canSubmitFromReviewer(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestStatus::WAITING_REVIEW]);
    }


    /**
     * Проверяет, может ли инициатор отправить запрос
     */
    public function canApproveFromReviewer(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestStatus::WAITING_REVIEW]);
    }


    /**
     * Проверяет, может ли инициатор отменить запрос
     */
    public function canCancelFromReviewer(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestStatus::WAITING_PROVIDER, pb2bDocflowRequestStatus::WAITING_REVIEW, pb2bDocflowRequestStatus::REJECTED]);
    }


    /**
     * Проверяет, может ли поставщик отправить запрос
     */
    public function canSubmitFromProvider(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestStatus::WAITING_PROVIDER, pb2bDocflowRequestStatus::REJECTED]);
    }


    /**
     * Проверяет, может ли поставщик отозвать запрос
     */
    public function canRevokeFromProvider(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestStatus::WAITING_REVIEW]);
    }


    /**
     * Проверяет, может ли поставщик отменить запрос
     */
    public function canCancelFromProvider(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestStatus::WAITING_PROVIDER, pb2bDocflowRequestStatus::WAITING_REVIEW, pb2bDocflowRequestStatus::REJECTED]);
    }


    /**
     * Запускает процесс отправки запроса и элементов этого запроса
     */
    public function applySubmit(): void
    {
        foreach ($this->getItems() as $item) $item->applySubmit();
        $this->changeStatusAndCreateHistory(pb2bDocflowRequestStatus::WAITING_REVIEW);
    }


    /**
     * Запускает процесс утверждения запроса и элементов этого запроса
     */
    public function applyApprove(): void
    {
        foreach ($this->getItems() as $item) $item->applyAccepted();
        $this->changeStatusAndCreateHistory(pb2bDocflowRequestStatus::APPROVED);
    }


    /**
     * Запускает процесс отзывания запроса и элементов этого запроса
     */
    public function applyRevoke(): void
    {
        foreach ($this->getItems() as $item) $item->applyRevoke();
        $this->changeStatusAndCreateHistory(pb2bDocflowRequestStatus::WAITING_PROVIDER);
    }


    /**
     * Запускает процесс отмены запроса и элементов этого запроса
     */
    public function applyCancel(): void
    {
        foreach ($this->getItems() as $item) $item->applyCancel();
        $this->changeStatusAndCreateHistory(pb2bDocflowRequestStatus::CANCELLED);
    }




//    protected function afterSave(array &$result): void
//    {
//        parent::afterSave($result);
//
//        if (empty($result['error']) && isset($result['new'])) {
//            if (isset($this->model->fields['file_set_id'])) {
//                $file_set_id = (int) ($this->data['file_set_id'] ?? 0);
//                if (!$file_set_id) {
//                    $file_set = new waproFileSet();
//                    $file_set_id = (int) $file_set->getId();
//                    $this->model->updateById($this->id, array('file_set_id' => $file_set_id));
//                    $this->data['file_set_id'] = $file_set_id;
//                }
//            }
//        }
//    }

//    protected function afterDelete(array &$result): void
//    {
//        parent::afterDelete($result);
//        if (empty($result['error'])) {
//            $this->docflowRequestItemsModel->deleteByField('request_id', $this->id);
//
//            $file_set_id = (int) ($this->data['file_set_id'] ?? 0);
//            if ($file_set_id) {
//                $set = new waproFileSet($file_set_id);
//                $set->clear();
//            }
//
//            $this->docflowRequestLogModel->setFetch('all');
//            $this->docflowRequestLogModel->setSelect(array(
//                'id' => null,
//                'file_set_id' => null,
//            ));
//            $this->docflowRequestLogModel->setWhere(array(
//                'request_id' => array('simile' => '=', 'value' => (int) $this->id),
//            ));
//            $logs = $this->docflowRequestLogModel->queryRun();
//            if (!empty($logs)) {
//                foreach ($logs as $log) {
//                    $log_file_set_id = (int) ($log['file_set_id'] ?? 0);
//                    if ($log_file_set_id > 0) {
//                        $set = new waproFileSet($log_file_set_id);
//                        $set->clear();
//                    }
//                }
//                $this->docflowRequestLogModel->deleteByField('request_id', (int) $this->id);
//            }
//        }
//    }

    protected function getConfigFields()
    {
        return pb2bWaproHelper::getFields($this->class_name);
    }

    public function createItemsFromTemplate(array $template_items, int $template_file_set_id = 0): array
    {
        if (empty($this->id)) {
            return array('error' => true, 'message' => 'Процесс не создан');
        }
        if (empty($template_items)) {
            return array('error' => true, 'message' => 'Элементы шаблона не найдены');
        }

        $request_file_set_id = (int) ($this->data['file_set_id'] ?? 0);
        if (!$request_file_set_id) {
            return array('error' => true, 'message' => 'У процесса отсутствует file_set_id');
        }

        $item_statuses = pb2bWaproHelper::getConfigOption('docflow_request_item_statuses', 'code');
        $item_status = (int) ($item_statuses['waiting_provider']['id'] ?? 1);
        $created = array();
        $sort_counter = 1;

        foreach ($template_items as $template_item) {
            $template_item_id = (int) ($template_item['id'] ?? 0);
            $item_data = array(
                'request_id' => $this->id,
                'reviewer_name' => trim((string) ($template_item['name'] ?? '')),
                'reviewer_comment' => $template_item['comment'] ?? null,
                'status' => $item_status,
                'sort' => (int) ($template_item['sort'] ?? $sort_counter),
            );

            $validate_result = pb2bWaproHelper::validate($item_data, 'docflow_request_item');
            if (!empty($validate_result['error'])) {
                return $validate_result;
            }

            $request_item_id = (int) $this->docflowRequestItemsModel->insert($item_data);
            if (!$request_item_id) {
                return array('error' => true, 'message' => 'Не удалось создать элемент процесса');
            }

            $source_file_id = (int) ($template_item['file_id'] ?? 0);
            $sample_file_id = 0;
            if ($source_file_id) {
                $copy_result = $this->copySampleFileToRequest(
                    $source_file_id,
                    $template_file_set_id,
                    $request_file_set_id,
                    $request_item_id,
                    $template_item_id
                );
                if (!empty($copy_result['error'])) {
                    return $copy_result;
                }

                $sample_file_id = (int) ($copy_result['file_id'] ?? 0);
                $this->docflowRequestItemsModel->updateById($request_item_id, array('sample_file_id' => $sample_file_id));
            }

            $created[] = array(
                'request_item_id' => $request_item_id,
                'template_item_id' => $template_item_id,
                'sample_file_id' => $sample_file_id,
            );
            $sort_counter++;
        }

        return array(
            'error' => false,
            'count' => count($created),
            'items' => $created,
        );
    }

    protected function copySampleFileToRequest(
        int $source_file_id,
        int $template_file_set_id,
        int $request_file_set_id,
        int $request_item_id,
        int $template_item_id
    ): array {
        $file_items_model = new waproFileSetItemsModel();
        $source_file = $file_items_model->getById($source_file_id);
        if (empty($source_file)) {
            return array('error' => true, 'message' => 'Файл шаблона не найден');
        }
        if ($template_file_set_id && (int) $source_file['set_id'] !== $template_file_set_id) {
            return array('error' => true, 'message' => 'Файл не принадлежит file_set шаблона');
        }

        $insert = $source_file;
        unset($insert['id']);
        $insert['set_id'] = $request_file_set_id;

        $extra = array();
        if (!empty($source_file['extra'])) {
            $decoded = json_decode($source_file['extra'], true);
            if (is_array($decoded)) {
                $extra = $decoded;
            }
        }
        $extra['request_item_id'] = $request_item_id;
        $extra['template_item_id'] = $template_item_id;
        $extra['source_file_id'] = $source_file_id;
        $insert['extra'] = json_encode($extra, JSON_UNESCAPED_UNICODE);

        $new_file_id = (int) $file_items_model->insert($insert);
        if (!$new_file_id) {
            return array('error' => true, 'message' => 'Не удалось скопировать файл шаблона');
        }

        $app_id = pb2bWaproHelper::getAppId();
        $source_path = waproFileSet::getPath(
            $app_id,
            (int) $source_file['set_id'],
            (int) $source_file['is_public'],
            (int) $source_file['id'],
            (string) $source_file['ext']
        );
        if (!file_exists($source_path)) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Файл шаблона отсутствует на сервере');
        }

        $target_dir = waproFileSet::getPath(
            $app_id,
            $request_file_set_id,
            (int) $source_file['is_public']
        );
        if (!file_exists($target_dir) && !waFiles::create($target_dir)) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Не удалось создать директорию для файлов процесса');
        }
        if (file_exists($target_dir) && !is_writable($target_dir)) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Нет прав записи в директорию файлов процесса');
        }

        $target_path = waproFileSet::getPath(
            $app_id,
            $request_file_set_id,
            (int) $source_file['is_public'],
            $new_file_id,
            (string) $source_file['ext']
        );

        try {
            waFiles::copy($source_path, $target_path);
        } catch (Exception $e) {
            $file_items_model->deleteById($new_file_id);
            return array('error' => true, 'message' => 'Не удалось скопировать файл шаблона в процесс');
        }

        return array('error' => false, 'file_id' => $new_file_id);
    }

    public function uploadItemFromProvider(
        int $provider_id,
        int $request_item_id,
        string $input_name = 'file',
        ?string $provider_comment = null
    ): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');
        if (!$provider_id) return array('error' => true, 'message' => 'Не передан provider_id');

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $waiting_provider_status = (int) ($request_statuses['waiting_provider']['id'] ?? 1);
        if ((int) ($this->data['status'] ?? 0) !== $waiting_provider_status) {
            return array('error' => true, 'message' => 'Загрузка недоступна в текущем статусе процесса');
        }
        
        if ((int)($this->data['provider_id'] ?? 0) !== $provider_id) {
            return array('error' => true, 'message' => 'У компании нет доступа к этому процессу');
        }
        
        if (!$request_item_id) {
            return array('error' => true, 'message' => 'Не передан request_item_id');
        }

        $request_item = $this->docflowRequestItemsModel->getById($request_item_id);
        if (empty($request_item) || (int) ($request_item['request_id'] ?? 0) !== (int) $this->id) {
            return array('error' => true, 'message' => 'Элемент процесса не найден');
        }

        $request_file_set_id = (int) ($this->data['file_set_id'] ?? 0);
        if (!$request_file_set_id) {
            return array('error' => true, 'message' => 'У процесса отсутствует file_set_id');
        }

        $set = new waproFileSet($request_file_set_id);
        $upload = $set->uploadFromPost($input_name, array(
            'is_public' => 0,
            'extra' => json_encode(array('request_item_id' => $request_item_id), JSON_UNESCAPED_UNICODE),
        ));
        $upload_result = array();
        if (!empty($upload) && is_array($upload)) $upload_result = reset($upload);
        if (empty($upload_result) || (int) ($upload_result['result'] ?? 0) !== 1) {
            return array('error' => true, 'message' => $upload_result['message'] ?? 'Не удалось загрузить файл');
        }

        $provider_file_id = (int) ($upload_result['file_id'] ?? 0);
        if (!$provider_file_id) return array('error' => true, 'message' => 'Не получен ID загруженного файла');

        $item_statuses = pb2bWaproHelper::getConfigOption('docflow_request_item_statuses', 'code');
        $item_status = (int) ($item_statuses['uploaded']['id'] ?? 2);

        if (is_string($provider_comment)) {
            $provider_comment = trim($provider_comment);
            if ($provider_comment === '') {
                $provider_comment = null;
            }
        } else {
            $provider_comment = null;
        }

        $now = date('Y-m-d H:i:s');
        $update_item_data = array(
            'provider_file_id' => $provider_file_id,
            'status' => $item_status,
            'provider_uploaded_datetime' => $now,
            'update_datetime' => $now,
        );
        if ($provider_comment !== null) {
            $update_item_data['provider_comment'] = $provider_comment;
        }
        $this->docflowRequestItemsModel->updateById($request_item_id, $update_item_data);

        return array(
            'error' => false,
            'message' => 'Файл загружен',
            'request_id' => (int) $this->id,
            'request_item_id' => $request_item_id,
            'provider_file_id' => $provider_file_id,
            'item_status' => $item_status,
            'provider_comment' => $provider_comment,
        );
    }

    public function submitFromProvider(int $provider_id): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');
        if (!$provider_id) return array('error' => true, 'message' => 'Не передан provider_id');

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $waiting_provider_status = (int) ($request_statuses['waiting_provider']['id'] ?? 1);
        if ((int) ($this->data['status'] ?? 0) !== $waiting_provider_status) {
            return array('error' => true, 'message' => 'Отправка на проверку недоступна в текущем статусе процесса');
        }
        
        if ((int) ($this->data['provider_id'] ?? 0) !== $provider_id) {
            return array('error' => true, 'message' => 'У компании нет доступа к этому процессу');
        }

        $this->docflowRequestItemsModel->setFetch('all');
        $this->docflowRequestItemsModel->setSelect(array(
            'id' => null,
            'reviewer_name' => null,
            'provider_file_id' => null,
            'status' => null,
        ));
        $this->docflowRequestItemsModel->setWhere(array(
            'request_id' => array('simile' => '=', 'value' => (int) $this->id),
        ));
        $this->docflowRequestItemsModel->setOrderBy(array(
            'sort' => 'ASC',
            'id' => 'ASC',
        ));
        $items = $this->docflowRequestItemsModel->queryRun();
        if (empty($items)) return array('error' => true, 'message' => 'В процессе нет элементов');

        $missing_items = array();
        foreach ($items as $item) {
            if ((int) ($item['provider_file_id'] ?? 0) <= 0) {
                $missing_items[] = array(
                    'id' => (int) ($item['id'] ?? 0),
                    'name' => (string) ($item['reviewer_name'] ?? ''),
                );
            }
        }
        if (!empty($missing_items)) {
            return array(
                'error' => true,
                'message' => 'Не все документы загружены',
                'missing_items' => $missing_items,
            );
        }

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $request_status = (int) ($request_statuses['waiting_review']['id'] ?? 2);
        $status_from = (int) ($this->data['status'] ?? 0);
        $rejected_status = (int) ($request_statuses['rejected']['id'] ?? 4);
        $history_status_code = $status_from === $rejected_status ? 'request_resubmitted' : 'request_submitted';

        $now = date('Y-m-d H:i:s');
        $this->model->updateById($this->id, array(
            'status' => $request_status,
            'update_datetime' => $now,
        ));
        $this->data['status'] = $request_status;
        $this->data['update_datetime'] = $now;
        $this->addHistory(
            $history_status_code,
            $status_from,
            $request_status,
            true,
            (int) $provider_id
        );

        return array(
            'error' => false,
            'message' => 'Процесс отправлен на проверку',
            'request_id' => (int) $this->id,
            'request_status' => $request_status,
        );
    }

    public function approveFromReviewer(int $reviewer_id): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');
        if (!$reviewer_id) return array('error' => true, 'message' => 'Не передан reviewer_id');

        if ((int) ($this->data['reviewer_id'] ?? 0) !== $reviewer_id) {
            return array('error' => true, 'message' => 'У компании нет доступа к этому процессу');
        }

        $reviewer = new pb2bCompany($reviewer_id);
        if (!$reviewer->id || empty($reviewer->data['buyer'])) {
            return array('error' => true, 'message' => 'Решение может принимать только компания-покупатель');
        }

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $waiting_review_status = (int) ($request_statuses['waiting_review']['id'] ?? 2);
        $approved_status = (int) ($request_statuses['approved']['id'] ?? 3);
        $status_from = (int) ($this->data['status'] ?? 0);
        if ((int) ($this->data['status'] ?? 0) !== $waiting_review_status) {
            return array('error' => true, 'message' => 'Решение по заявке можно принять только в статусе "Ожидает проверки"');
        }

        $this->docflowRequestItemsModel->setFetch('all');
        $this->docflowRequestItemsModel->setSelect(array(
            'id' => null,
            'reviewer_name' => null,
            'provider_file_id' => null,
        ));
        $this->docflowRequestItemsModel->setWhere(array(
            'request_id' => array('simile' => '=', 'value' => (int) $this->id),
        ));
        $this->docflowRequestItemsModel->setOrderBy(array(
            'sort' => 'ASC',
            'id' => 'ASC',
        ));
        $items = $this->docflowRequestItemsModel->queryRun();
        if (empty($items)) return array('error' => true, 'message' => 'В процессе нет элементов');

        $missing_items = array();
        foreach ($items as $item) {
            if ((int) ($item['provider_file_id'] ?? 0) <= 0) {
                $missing_items[] = array(
                    'id' => (int) ($item['id'] ?? 0),
                    'name' => (string) ($item['reviewer_name'] ?? ''),
                );
            }
        }
        if (!empty($missing_items)) {
            return array(
                'error' => true,
                'message' => 'Не все документы загружены',
                'missing_items' => $missing_items,
            );
        }

        $item_statuses = pb2bWaproHelper::getConfigOption('docflow_request_item_statuses', 'code');
        $accepted_item_status = (int) ($item_statuses['accepted']['id'] ?? 3);

        $now = date('Y-m-d H:i:s');
        foreach ($items as $item) {
            $item_id = (int) ($item['id'] ?? 0);
            if (!$item_id) {
                continue;
            }
            $this->docflowRequestItemsModel->updateById($item_id, array(
                'status' => $accepted_item_status,
                'accepted_datetime' => $now,
                'update_datetime' => $now,
            ));
        }

        $this->model->updateById($this->id, array(
            'status' => $approved_status,
            'approved_datetime' => $now,
            'update_datetime' => $now,
        ));
        $this->data['status'] = $approved_status;
        $this->data['approved_datetime'] = $now;
        $this->data['update_datetime'] = $now;
        $this->addHistory(
            'request_approved',
            $status_from,
            $approved_status,
            true,
            (int) $reviewer_id
        );

        return array(
            'error' => false,
            'message' => 'Заявка утверждена',
            'request_id' => (int) $this->id,
            'request_status' => $approved_status,
            'approved_datetime' => $now,
        );
    }

    public function rejectFromReviewer(int $reviewer_id, ?string $comment = null, array $item_reasons = array()): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');
        if (!$reviewer_id) return array('error' => true, 'message' => 'Не передан reviewer_id');

        if ((int) ($this->data['reviewer_id'] ?? 0) !== $reviewer_id) {
            return array('error' => true, 'message' => 'У компании нет доступа к этому процессу');
        }

        $reviewer = new pb2bCompany($reviewer_id);
        if (!$reviewer->id || empty($reviewer->data['buyer'])) {
            return array('error' => true, 'message' => 'Решение может принимать только компания-покупатель');
        }

        if (is_string($comment)) {
            $comment = trim($comment);
            if ($comment === '') {
                $comment = null;
            }
        } else {
            $comment = null;
        }

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $waiting_review_status = (int) ($request_statuses['waiting_review']['id'] ?? 2);
        $rejected_status = (int) ($request_statuses['rejected']['id'] ?? 4);
        $status_from = (int) ($this->data['status'] ?? 0);
        if ((int) ($this->data['status'] ?? 0) !== $waiting_review_status) {
            return array('error' => true, 'message' => 'Решение по заявке можно принять только в статусе "Ожидает проверки"');
        }

        $reason_map = array();
        if (!empty($item_reasons)) {
            foreach ($item_reasons as $key => $reason_item) {
                $item_id = 0;
                $item_comment = null;

                if (is_array($reason_item)) {
                    $item_id = (int) ($reason_item['request_item_id'] ?? ($reason_item['item_id'] ?? ($reason_item['id'] ?? 0)));
                    if (!$item_id && is_numeric($key)) {
                        $item_id = (int) $key;
                    }
                    $item_comment = $reason_item['comment'] ?? ($reason_item['reviewer_comment'] ?? ($reason_item['reason'] ?? null));
                } else {
                    if (is_numeric($key)) {
                        if (is_numeric($reason_item)) {
                            $item_id = (int) $reason_item;
                        } else {
                            $item_id = (int) $key;
                            $item_comment = $reason_item;
                        }
                    } else {
                        $item_id = (int) $key;
                        $item_comment = $reason_item;
                    }
                }

                if ($item_id <= 0) continue;
                if (is_string($item_comment)) {
                    $item_comment = trim($item_comment);
                    if ($item_comment === '') {
                        $item_comment = null;
                    }
                } else {
                    $item_comment = null;
                }

                $reason_map[$item_id] = $item_comment;
            }
        }

        if (empty($reason_map) && empty($comment)) return array('error' => true, 'message' => 'Укажите причину отклонения');

        $this->docflowRequestItemsModel->setFetch('all');
        $this->docflowRequestItemsModel->setSelect(array(
            'id' => null,
            'reviewer_name' => null,
            'provider_file_id' => null,
        ));
        $this->docflowRequestItemsModel->setWhere(array(
            'request_id' => array('simile' => '=', 'value' => (int) $this->id),
        ));
        $this->docflowRequestItemsModel->setOrderBy(array(
            'sort' => 'ASC',
            'id' => 'ASC',
        ));
        $items = $this->docflowRequestItemsModel->queryRun();
        if (empty($items)) return array('error' => true, 'message' => 'В процессе нет элементов');

        $items_map = array();
        foreach ($items as $item) {
            $items_map[(int) ($item['id'] ?? 0)] = $item;
        }

        if (!empty($reason_map)) {
            $missing_item_ids = array();
            foreach ($reason_map as $item_id => $item_comment) {
                if (empty($items_map[$item_id])) {
                    $missing_item_ids[] = $item_id;
                }
            }
            if (!empty($missing_item_ids)) {
                return array(
                    'error' => true,
                    'message' => 'Не найдены элементы процесса: '.implode(', ', $missing_item_ids),
                );
            }
        }

        $item_statuses = pb2bWaproHelper::getConfigOption('docflow_request_item_statuses', 'code');
        $rejected_item_status = (int) ($item_statuses['rejected']['id'] ?? 4);

        $now = date('Y-m-d H:i:s');
        if (!empty($reason_map)) {
            foreach ($reason_map as $item_id => $item_comment) {
                $update_data = array(
                    'status' => $rejected_item_status,
                    'accepted_datetime' => null,
                    'update_datetime' => $now,
                );
                if (!empty($item_comment)) {
                    $update_data['reviewer_comment'] = $item_comment;
                }
                $this->docflowRequestItemsModel->updateById((int) $item_id, $update_data);
            }
        }

        $request_comment = $comment;
        if ($request_comment === null) $request_comment = $this->data['comment'] ?? null;

        $this->model->updateById($this->id, array(
            'status' => $rejected_status,
            'approved_datetime' => null,
            'comment' => $request_comment,
            'update_datetime' => $now,
        ));
        $this->data['status'] = $rejected_status;
        $this->data['approved_datetime'] = null;
        $this->data['comment'] = $request_comment;
        $this->data['update_datetime'] = $now;
        $this->addHistory(
            'request_rejected',
            $status_from,
            $rejected_status,
            true,
            (int) $reviewer_id,
            $request_comment
        );

        return array(
            'error' => false,
            'message' => 'Заявка отклонена',
            'request_id' => (int) $this->id,
            'request_status' => $rejected_status,
            'rejected_items_count' => count($reason_map),
        );
    }

    //cancel
    public function cancelFromReviewer(int $reviewer_id, ?string $comment = null): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');
        if (!$reviewer_id) return array('error' => true, 'message' => 'Не передан reviewer_id');

        if ((int) ($this->data['reviewer_id'] ?? 0) !== $reviewer_id) {
            return array('error' => true, 'message' => 'У компании нет доступа к этому процессу');
        }

        $reviewer = new pb2bCompany($reviewer_id);
        if (!$reviewer->id || empty($reviewer->data['buyer'])) {
            return array('error' => true, 'message' => 'Отменить процесс может только компания-покупатель');
        }

        $cancel_result = $this->applyCancelToProcess($comment);
        if (!empty($cancel_result['error'])) {
            return $cancel_result;
        }
//        $this->addHistory(
//            'request_cancelled',
//            (int) ($cancel_result['from_status'] ?? 0),
//            (int) ($cancel_result['request_status'] ?? 0),
//            true,
//            (int) $reviewer_id,
//            $comment
//        );

        return array(
            'error' => false,
            'message' => 'Процесс отменён',
            'request_id' => (int) $this->id,
            'request_status' => $cancel_result['request_status'],
        );
    }

    public function cancelFromProvider(int $provider_id, ?string $comment = null): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');
        if (!$provider_id) return array('error' => true, 'message' => 'Не передан provider_id');

        if ((int) ($this->data['provider_id'] ?? 0) !== $provider_id) {
            return array('error' => true, 'message' => 'У компании нет доступа к этому процессу');
        }

        $provider = new pb2bCompany($provider_id);
        if (!$provider->id || empty($provider->data['supplier'])) {
            return array('error' => true, 'message' => 'Отменить процесс может только компания-поставщик');
        }

        $cancel_result = $this->applyCancelToProcess($comment);
        if (!empty($cancel_result['error'])) {
            return $cancel_result;
        }
        $this->addHistory(
            'request_cancelled',
            (int) ($cancel_result['from_status'] ?? 0),
            (int) ($cancel_result['request_status'] ?? 0),
            true,
            (int) $provider_id,
            $comment
        );

        return array(
            'error' => false,
            'message' => 'Процесс отменён',
            'request_id' => (int) $this->id,
            'request_status' => $cancel_result['request_status'],
        );
    }
    
    protected function applyCancelToProcess(?string $comment): array
    {
        if (is_string($comment)) {
            $comment = trim($comment);
            if ($comment === '') {
                $comment = null;
            }
        } else {
            $comment = null;
        }

        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'code');
        $waiting_provider = (int) ($request_statuses['waiting_provider']['id'] ?? 1);
        $waiting_review = (int) ($request_statuses['waiting_review']['id'] ?? 2);
        $approved_status = (int) ($request_statuses['approved']['id'] ?? 3);
        $cancelled_status = (int) ($request_statuses['cancelled']['id'] ?? 5);

        $current = (int) ($this->data['status'] ?? 0);
        if (
            $current !== $waiting_provider
            && $current !== $waiting_review
            && $current !== $approved_status
        ) {
            return array(
                'error' => true,
                'message' => 'Процесс можно отменить только в статусах "Ожидает документы", "Ожидает проверки" или "Одобрено"',
            );
        }

        $item_statuses = pb2bWaproHelper::getConfigOption('docflow_request_item_statuses', 'code');
        $cancelled_item_status = (int) ($item_statuses['cancelled']['id'] ?? 5);

        $this->docflowRequestItemsModel->setFetch('all');
        $this->docflowRequestItemsModel->setSelect(array('id' => null));
        $this->docflowRequestItemsModel->setWhere(array(
            'request_id' => array('simile' => '=', 'value' => (int) $this->id),
        ));
        $items = $this->docflowRequestItemsModel->queryRun();

        $now = date('Y-m-d H:i:s');
        foreach ($items as $item) {
            $item_id = (int) ($item['id'] ?? 0);
            if (!$item_id) {
                continue;
            }
            $this->docflowRequestItemsModel->updateById($item_id, array(
                'status' => $cancelled_item_status,
                'accepted_datetime' => null,
                'update_datetime' => $now,
            ));
        }

        $request_comment = $this->data['comment'] ?? null;
        if ($comment !== null) {
            $request_comment = $comment;
        }

        $this->model->updateById($this->id, array(
            'status' => $cancelled_status,
            'approved_datetime' => null,
            'comment' => $request_comment,
            'update_datetime' => $now,
        ));
        $this->data['status'] = $cancelled_status;
        $this->data['approved_datetime'] = null;
        $this->data['comment'] = $request_comment;
        $this->data['update_datetime'] = $now;

        return array(
            'request_status' => $cancelled_status,
            'from_status' => $current,
        );
    }

    //History
    public function addHistory(
        string $log_status_code,
        ?int $from_status_id = null,
        ?int $to_status_id = null,
        bool $with_files = false,
        ?int $author_company_id = null,
        ?string $comment = null
    ): void {
        if (empty($this->id)) return;

        $log_statuses = pb2bWaproHelper::getConfigOption('docflow_request_log_statuses', 'code');
        $log_status_id = (int) ($log_statuses[$log_status_code]['id'] ?? 0);
        if ($log_status_id <= 0) return;

        if (is_string($comment)) {
            $comment = trim($comment);
            if ($comment === '') $comment = null;
        } else {
            $comment = null;
        }

        $history_file_set_id = null;
        $history_file_set = null;

        if ($with_files) {
            $request_file_set_id = (int) ($this->data['file_set_id'] ?? 0);
            $provider_file_ids = array();
            $collection = new pb2bDocflowRequestCollection("col.id={$this->id}&items.provider_file_id>0");
            $rows = $collection->getCollection(array(
                'key' => false,
                'select' => array(
                    array('field' => 'provider_file_id', 'table' => 'DTI', 'as' => 'provider_file_id'),
                ),
            ));
            foreach ($rows as $row) {
                $provider_file_id = (int) ($row['provider_file_id'] ?? 0);
                if ($provider_file_id > 0) $provider_file_ids[$provider_file_id] = $provider_file_id;
            }
            $provider_file_ids = array_values($provider_file_ids);

            if ($request_file_set_id > 0 && !empty($provider_file_ids)) {
                $request_file_set = new waproFileSet($request_file_set_id);
                if ((int) $request_file_set->getId() > 0) {
                    $history_file_set = $request_file_set->makeCloneByIds($provider_file_ids);
                    $history_file_set_id = (int) $history_file_set->getId();
                    if ($history_file_set_id > 0 && count($history_file_set->getItems(true)) === 0) {
                        $history_file_set->clear();
                        $history_file_set_id = null;
                        $history_file_set = null;
                    }
                }
            }
        }

        $now = date('Y-m-d H:i:s');
        $this->docflowRequestLogModel->insert(array(
            'request_id' => (int) $this->id,
            'log_status_id' => $log_status_id,
            'request_status_from_id' => $from_status_id ? (int) $from_status_id : null,
            'request_status_to_id' => $to_status_id ? (int) $to_status_id : null,
            'author_company_id' => $author_company_id ? (int) $author_company_id : null,
            'file_set_id' => $history_file_set_id,
            'comment' => $comment,
            'create_datetime' => $now,
        ));
    }

    public function getHistory(array $params = array()): array
    {
        if (empty($this->id)) return array('error' => true, 'message' => 'Процесс не найден');

        $start = max(0, (int) ($params['start'] ?? 0));
        $limit = max(1, (int) ($params['limit'] ?? 100));

        $this->docflowRequestLogModel->setFetch('all');
        $this->docflowRequestLogModel->setSelect(array(
            'id' => null,
            'log_status_id' => null,
            'request_status_from_id' => null,
            'request_status_to_id' => null,
            'author_company_id' => null,
            'file_set_id' => null,
            'comment' => null,
            'create_datetime' => null,
        ));
        $this->docflowRequestLogModel->setWhere(array(
            'request_id' => array('simile' => '=', 'value' => (int) $this->id),
        ));
        $this->docflowRequestLogModel->setOrderBy(array(
            'create_datetime' => 'DESC',
            'id' => 'DESC',
        ));
        $logs = $this->docflowRequestLogModel->queryRun();
        if (empty($logs)) {
            return array(
                'error' => false,
                'request_id' => (int) $this->id,
                'events' => array(),
                'total' => 0,
            );
        }

        $total = count($logs);
        $logs = array_slice($logs, $start, $limit);

        $log_statuses = pb2bWaproHelper::getConfigOption('docflow_request_log_statuses', 'id');
        $request_statuses = pb2bWaproHelper::getConfigOption('docflow_request_statuses', 'id');
        $documents_by_set = array();
        $events = array();

        foreach ($logs as $log) {
            $log_status_id = (int) ($log['log_status_id'] ?? 0);
            $status_from_id = (int) ($log['request_status_from_id'] ?? 0);
            $status_to_id = (int) ($log['request_status_to_id'] ?? 0);
            $file_set_id = (int) ($log['file_set_id'] ?? 0);

            $title = (string) ($log_statuses[$log_status_id]['title'] ?? 'Статус процесса изменён');
            $description = (string) ($log_statuses[$log_status_id]['description'] ?? '');
            $documents = array();

            if ($file_set_id > 0) {
                if (!isset($documents_by_set[$file_set_id])) {
                    $set = new waproFileSet($file_set_id);
                    $set_documents = $set->getItems(true);
                    $prepared_documents = array();

                    foreach ($set_documents as $set_document) {
                        $file_id = (int) ($set_document['id'] ?? 0);
                        if ($file_id <= 0) continue;

                        $name = trim((string) ($set_document['name'] ?? ''));
                        if ($name === '') $name = trim((string) ($set_document['filename'] ?? ''));
                        if ($name === '') $name = trim((string) ($set_document['original_filename'] ?? ''));
                        if ($name === '') $name = 'Файл #'.$file_id;

                        $prepared_documents[] = array(
                            'file_id' => $file_id,
                            'name' => $name,
                        );
                    }
                    $documents_by_set[$file_set_id] = $prepared_documents;
                }

                $documents = $documents_by_set[$file_set_id];
                foreach ($documents as &$document) {
                    $document['request_log_id'] = (int) ($log['id'] ?? 0);
                }
                unset($document);
            }

            $events[] = array(
                'id' => (int) ($log['id'] ?? 0),
                'log_status_id' => $log_status_id,
                'log_status_code' => $log_statuses[$log_status_id]['code'] ?? null,
                'event_datetime' => $log['create_datetime'] ?? null,
                'title' => $title,
                'description' => $description,
                'status_from_id' => $status_from_id ?: null,
                'status_from_name' => $request_statuses[$status_from_id]['name'] ?? null,
                'status_to_id' => $status_to_id ?: null,
                'status_to_name' => $request_statuses[$status_to_id]['name'] ?? null,
                'author_company_id' => (int) ($log['author_company_id'] ?? 0) ?: null,
                'comment' => $log['comment'] ?? null,
                'documents_count' => count($documents),
                'documents' => array_values($documents),
            );
        }

        return array(
            'error' => false,
            'request_id' => (int) $this->id,
            'events' => $events,
            'total' => $total,
        );
    }

    protected function generateProcedureCode(array &$data): void
    {
        if (!isset($this->model->fields['procedure_code'])) return;

        $process_type = (int) ($data['process_type'] ?? ($this->data['process_type'] ?? 0));
        if ($process_type <= 0) return;

        $process_types = pb2bWaproHelper::getConfigOption('docflow_process_types');
        $tender_type_id = (int) ($process_types[$process_type]['tender_type_id'] ?? $process_type);

        $process_code = str_pad((string) $tender_type_id, 2, '0', STR_PAD_LEFT);
        $codes = pb2bWaproHelper::getConfigOption('tender_codes');
        if (!empty($codes[$tender_type_id]['code'])) {
            $process_code = (string) $codes[$tender_type_id]['code'];
        }

        $year = (int) date('Y');
        $where = array(
            'process_type' => array('simile' => '=', 'value' => $process_type),
        );
        if (isset($this->model->fields['create_datetime'])) {
            $where['create_datetime'] = array(
                'simile' => 'BETWEEN',
                'value' => array(
                    'from' => sprintf('%04d-01-01 00:00:00', $year),
                    'to' => sprintf('%04d-12-31 23:59:59', $year),
                ),
            );
        }

        $this->model->setFetch('field');
        $this->model->setSelect(array(
            array('func' => 'count', 'params' => array(array('field' => 'id'))),
        ));
        $this->model->setWhere($where);
        $sequence = (int) $this->model->queryRun() + 1;

        $data['procedure_code'] = $process_code
            .'-'.substr((string) $year, -2)
            .'-'.str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);
    }
}
