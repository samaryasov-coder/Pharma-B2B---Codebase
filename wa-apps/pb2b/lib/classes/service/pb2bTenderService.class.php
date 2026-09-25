<?php

class pb2bTenderService extends pb2bBaseService
{
    protected pb2bTenderStatusService $statusService;
    protected pb2bProcedureCodeService $procedureCodeService;

    public function __construct(
        ?pb2bTenderStatusService $statusService = null,
        ?pb2bProcedureCodeService $procedureCodeService = null
    ) {
        $this->statusService = $statusService ?? new pb2bTenderStatusService();
        $this->procedureCodeService = $procedureCodeService ?? new pb2bProcedureCodeService();
    }

    private function getBuyerCompanyWithAssert(int $company_id): pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        if (!$company->id || !$company->isBuyer()) {
            throw new waException('Не найдена компания-покупатель', pb2bHttpStatus::NOT_FOUND);
        }

        return $company;
    }

    private function getTenderWithAssert(int $tender_id): pb2bTender
    {
        $tender = new pb2bTender($tender_id);
        if (!$tender->id) {
            throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        }

        return $tender;
    }

    private function assertPolicy(bool $ok): void
    {
        if (!$ok) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
    }

    private function dtoHas(pb2bTenderDto $dto, string $property): bool
    {
        return (new ReflectionProperty($dto, $property))->isInitialized($dto);
    }

    private function getDraftStatusId(): int
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'code');

        return (int) ($statuses['draft']['id'] ?? 1);
    }

    private function resolveTypeCode(int $type_id): string
    {
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');

        return (string) ($types[$type_id]['code'] ?? '');
    }

    private function resolveActorContactId(?waContact $actor): int
    {
        if ($actor && $actor->getId()) {
            return (int) $actor->getId();
        }
        $user = wa()->getUser();

        return $user ? (int) $user->getId() : 0;
    }

    private function throwSaveError(array $result, string $fallback): void
    {
        throw new waException(
            (string) ($result['message'] ?? $fallback),
            pb2bHttpStatus::BAD_REQUEST
        );
    }

    private function throwStatusError(array $result): void
    {
        $message = (string) ($result['message'] ?? 'Ошибка смены статуса');
        $conflict_markers = [
            'уже в этом статусе',
            'Переход между этими статусами недоступен',
            'Публикация недоступна',
        ];
        foreach ($conflict_markers as $marker) {
            if (mb_stripos($message, $marker) !== false) {
                throw new waException($message, pb2bHttpStatus::CONFLICT);
            }
        }

        throw new waException($message, pb2bHttpStatus::BAD_REQUEST);
    }

    private function assertDraftEditable(pb2bTender $tender): void
    {
        if ((int) ($tender->data['status'] ?? 0) !== $this->getDraftStatusId()) {
            throw new waException(
                'Редактировать можно только черновик',
                pb2bHttpStatus::CONFLICT
            );
        }
    }

    private function loadOrganizerTender(int $tender_id, int $company_id, callable $policy): pb2bTender
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $this->assertPolicy($policy($tender, $company));

        return $tender;
    }

    /**
     * @throws waException
     */
    public function createFromBuyer(int $organizer_company_id, pb2bTenderDto $dto, ?waContact $actor = null): pb2bTender
    {
        $company = $this->getBuyerCompanyWithAssert($organizer_company_id);
        $this->assertPolicy(pb2bTenderPolicy::create($company));

        if (!$this->dtoHas($dto, 'type') || (int) $dto->type <= 0) {
            throw new waException('Не указан тип процедуры', pb2bHttpStatus::BAD_REQUEST);
        }

        $type_code = $this->resolveTypeCode((int) $dto->type);
        if ($type_code !== 'price_request') {
            throw new waException(
                'В этом срезе доступен только запрос цен',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        if (!$this->dtoHas($dto, 'title') || trim($dto->title) === '') {
            throw new waException('Укажите наименование', pb2bHttpStatus::BAD_REQUEST);
        }

        $number = $this->procedureCodeService->issue($type_code);

        $contact_id = $this->resolveActorContactId($actor);
        if ($contact_id <= 0) {
            throw new waException('Не указан ответственный контакт', pb2bHttpStatus::BAD_REQUEST);
        }

        $tender = new pb2bTender();
        if ($tender->findDuplicateNumber($number, (int) $company->id)) {
            throw new waException('Реестровый номер уже используется', pb2bHttpStatus::BAD_REQUEST);
        }

        $save_result = $tender->save([
            'type' => (int) $dto->type,
            'title' => trim($dto->title),
            'number' => $number,
            'organizer_company_id' => (int) $company->id,
            'responsible_contact_id' => $contact_id,
            'status' => $this->getDraftStatusId(),
        ]);
        if (!empty($save_result['error'])) {
            $this->throwSaveError($save_result, 'Не удалось создать тендер');
        }

        return $tender;
    }

    /**
     * @throws waException
     */
    public function updateFromBuyer(int $tender_id, int $company_id, pb2bTenderDto $dto): pb2bTender
    {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'update']
        );
        $this->assertDraftEditable($tender);

        $patch = $dto->toSaveArray();
        if ($this->dtoHas($dto, 'title') && trim((string) $dto->title) === '') {
            throw new waException('Укажите наименование', pb2bHttpStatus::BAD_REQUEST);
        }
        unset($patch['number']);
        if (array_key_exists('title', $patch)) {
            $patch['title'] = trim((string) $patch['title']);
        }

        if ($patch) {
            $save_data = $tender->data;
            unset($save_data['create_datetime'], $save_data['update_datetime']);
            foreach ($patch as $key => $value) {
                $save_data[$key] = $value;
            }
            $save_data['type'] = (int) ($tender->data['type'] ?? 0);
            $save_data['organizer_company_id'] = (int) ($tender->data['organizer_company_id'] ?? 0);
            $save_data['status'] = (int) ($tender->data['status'] ?? 0);
            $save_data['responsible_contact_id'] = (int) ($tender->data['responsible_contact_id'] ?? 0);

            $save_result = $tender->save($save_data);
            if (!empty($save_result['error'])) {
                $this->throwSaveError($save_result, 'Не удалось сохранить тендер');
            }
        }

        if ($dto->hasInvitations()) {
            $inv_result = $tender->replaceInvitations($dto->invitations, $company_id);
            if (!empty($inv_result['error'])) {
                $this->throwSaveError($inv_result, 'Не удалось сохранить приглашения');
            }
        }

        if ($dto->hasCriteria()) {
            $crit_result = $tender->replaceCriteria($dto->criteria, $company_id);
            if (!empty($crit_result['error'])) {
                $this->throwSaveError($crit_result, 'Не удалось сохранить критерии');
            }
        }

        if ($dto->hasItems()) {
            $items_result = $tender->replaceItems($dto->items, $company_id);
            if (!empty($items_result['error'])) {
                $this->throwSaveError($items_result, 'Не удалось сохранить позиции');
            }
        }

        if ($dto->hasDocuments()) {
            $docs_result = $tender->replaceDocuments($dto->documents, $company_id);
            if (!empty($docs_result['error'])) {
                $this->throwSaveError($docs_result, 'Не удалось сохранить документы');
            }
        }

        return $tender;
    }

    /**
     * @throws waException
     */
    public function publishFromBuyer(
        int $tender_id,
        int $company_id,
        ?string $reason = null,
        ?waContact $actor = null
    ): pb2bTender {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'publish']
        );

        $result = $this->statusService->publish($tender, $reason, $actor);
        if (!empty($result['error'])) {
            $this->throwStatusError($result);
        }

        return $tender;
    }

    /**
     * @throws waException
     */
    public function getFromBuyer(int $tender_id, int $company_id): pb2bTender
    {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'view']
        );
        $this->statusService->ensureReceptionOpen($tender);

        return $tender;
    }

    /**
     * Карточка + classifiers/invitations/criteria/items для GET.
     *
     * @return array{tender: array, classifiers: array, invitations: array, criteria: array, items: array, documents: array}
     * @throws waException
     */
    public function getDetailFromBuyer(int $tender_id, int $company_id): array
    {
        $tender = $this->getFromBuyer($tender_id, $company_id);
        $payload = (new pb2bTenderCollection())->getWithClassifiers((int) $tender->id);
        if (!empty($payload['error'])) {
            throw new waException(
                (string) ($payload['message'] ?? 'Тендер не найден'),
                pb2bHttpStatus::NOT_FOUND
            );
        }

        return [
            'tender' => pb2bTenderResource::make($tender)->resolve(),
            'classifiers' => (array) ($payload['classifiers'] ?? []),
            'invitations' => (array) ($payload['invitations'] ?? []),
            'criteria' => (array) ($payload['criteria'] ?? []),
            'items' => $this->serializeItems($tender),
            'documents' => $this->serializeDocuments($tender),
        ];
    }

    /**
     * @throws waException
     */
    public function listFromBuyer(int $company_id, array $filters = []): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);

        return (new pb2bTenderCollection())->getBuyerList((int) $company->id, $filters);
    }

    /**
     * Загрузка файла черновика тендера (ТЗ / позиция / документ).
     * Связь с item/document — на save; GC сирот — отдельно.
     *
     * @return array{file_link_id:int,filename:string,size:int,ext:string}
     * @throws waException
     */
    public function uploadFileFromBuyer(int $tender_id, int $company_id, waRequestFile $upload_file): array
    {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'uploadFile']
        );
        $this->assertDraftEditable($tender);

        if (!$upload_file->uploaded()) {
            throw new waException('Файл не загружен', pb2bHttpStatus::BAD_REQUEST);
        }

        $ext = strtolower((string) $upload_file->extension);
        $allowed = array('pdf', 'doc', 'docx');
        if (!in_array($ext, $allowed, true)) {
            throw new waException(
                'Допустимые форматы: PDF, DOC, DOCX',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $max_bytes = 10 * 1024 * 1024;
        if ((int) $upload_file->size > $max_bytes) {
            throw new waException(
                'Максимальный размер файла: 10 МБ',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $storage = new pb2bFileStorageService();
        $file_link = $storage->saveFileAndCreateLink(
            $upload_file,
            'tenders',
            $company_id
        );

        $file = $file_link->getFile();

        return array(
            'file_link_id' => (int) $file_link->id,
            'filename' => (string) ($file_link->data['filename'] ?? $upload_file->name),
            'size' => (int) ($file->data['size'] ?? $upload_file->size),
            'ext' => (string) ($file->data['ext'] ?? $ext),
        );
    }

    /**
     * @throws waException
     */
    public function replaceCriteriaFromBuyer(int $tender_id, int $company_id, array $rows): array
    {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'replaceCriteria']
        );
        $this->assertDraftEditable($tender);

        $result = $tender->replaceCriteria($rows, $company_id);
        if (!empty($result['error'])) {
            $this->throwSaveError($result, 'Не удалось сохранить критерии');
        }
        $result['tender_id'] = (int) $tender->id;

        return $result;
    }

    /**
     * @throws waException
     */
    public function replaceInvitationsFromBuyer(int $tender_id, int $company_id, array $supplier_company_ids): array
    {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'replaceInvitations']
        );
        $this->assertDraftEditable($tender);

        $result = $tender->replaceInvitations($supplier_company_ids, $company_id);
        if (!empty($result['error'])) {
            $this->throwSaveError($result, 'Не удалось сохранить приглашения');
        }
        $result['tender_id'] = (int) $tender->id;

        return $result;
    }

    /**
     * @throws waException
     */
    public function replaceClassifiersFromBuyer(int $tender_id, int $company_id, array $rows): array
    {
        $tender = $this->loadOrganizerTender(
            $tender_id,
            $company_id,
            [pb2bTenderPolicy::class, 'replaceClassifiers']
        );
        $this->assertDraftEditable($tender);

        $result = $tender->replaceClassifiers($rows, $company_id);
        if (!empty($result['error'])) {
            $this->throwSaveError($result, 'Не удалось сохранить классификаторы');
        }
        $result['tender_id'] = (int) $tender->id;

        return $result;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serializeItems(pb2bTender $tender): array
    {
        $out = array();
        foreach ($tender->getItemsForView() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $file_link_id = (int) ($row['file_link_id'] ?? 0);
            $out[] = array(
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'qty' => $row['qty'] ?? null,
                'unit' => $row['unit'] ?? null,
                'max_price_no_vat' => $row['max_price_no_vat'] ?? null,
                'vat_rate' => $row['vat_rate'] ?? null,
                'delivery_place' => $row['delivery_place'] ?? null,
                'comment' => $row['comment'] ?? null,
                'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                'file_name' => (string) ($row['file_name'] ?? ''),
                'sort' => (int) ($row['sort'] ?? 0),
            );
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serializeDocuments(pb2bTender $tender): array
    {
        $out = array();
        foreach ($tender->getDocumentsForView() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $file_link_id = (int) ($row['file_link_id'] ?? 0);
            $out[] = array(
                'id' => (int) ($row['id'] ?? 0),
                'kind' => (string) ($row['kind'] ?? ''),
                'name' => (string) ($row['name'] ?? ''),
                'description' => (string) ($row['description'] ?? ''),
                'is_required' => !empty($row['is_required']) ? 1 : 0,
                'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                'file_name' => (string) ($row['file_name'] ?? ''),
                'sort' => (int) ($row['sort'] ?? 0),
            );
        }

        return $out;
    }
}
