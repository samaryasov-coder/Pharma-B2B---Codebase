<?php

class pb2bTenderApplicationService extends pb2bBaseService
{
    public const FILE_DIR_NAME = 'tender_applications';

    protected pb2bTenderStatusService $statusService;
    protected pb2bFileStorageService $fileStorageService;

    public function __construct(
        ?pb2bTenderStatusService $statusService = null,
        ?pb2bFileStorageService $fileStorageService = null
    ) {
        $this->statusService = $statusService ?? new pb2bTenderStatusService();
        $this->fileStorageService = $fileStorageService ?? new pb2bFileStorageService();
    }

    /**
     * @return list<array{tender: array, application: ?array}>
     * @throws waException
     */
    public function listForSupplier(int $company_id): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $this->openDueReception();
        $tenders = $this->candidateTendersForSupplier((int) $company->id);
        if (!$tenders) {
            return array();
        }
        $tender_ids = array_map(static fn(pb2bTender $tender): int => (int) $tender->id, $tenders);
        $filters = array('col' => array(
            'supplier_company_id' => array('value' => (int) $company->id),
            'tender_id' => array('simile' => 'IN', 'value' => $tender_ids),
        ));
        $applications = (new pb2bTenderApplicationCollection($filters))->getCollection(array(
            'key' => 'tender_id',
            'select' => array('id' => null, 'tender_id' => null),
        ));
        $invitations = (new pb2bTenderInvitationCollection($filters))->getCollection(array(
            'key' => 'tender_id',
            'select' => array('tender_id' => null),
        ));

        $out = array();
        foreach ($tenders as $tender) {
            $tender_id = (int) $tender->id;
            if (!pb2bTenderPolicy::viewAsSupplier($tender, $company, isset($invitations[$tender_id]))) {
                continue;
            }
            $application = isset($applications[$tender_id])
                ? new pb2bTenderApplication((int) $applications[$tender_id]['id'])
                : null;
            $out[] = array(
                'tender' => pb2bTenderResource::make($tender)->forSupplier()->resolve(),
                'application' => $application
                    ? $this->serializeApplication($application, false, true, false, array('supplier' => $company->data))
                    : null,
                'card' => $this->supplierListCard($tender),
            );
        }

        return $out;
    }

    /**
     * @return array{tender: array, items: array, documents: array, criteria: array, application: ?array, gates: array}
     * @throws waException
     */
    public function getNotice(int $tender_id, int $company_id, bool $sync_reception = true): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::viewAsSupplier($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        if ($sync_reception) {
            $this->statusService->ensureReceptionOpen($tender);
        }
        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if ($application) {
            if (!pb2bTenderApplicationPolicy::view($application, $company)) {
                throw new waException('Заявка не доступна', pb2bHttpStatus::FORBIDDEN);
            }
        }

        return array(
            'tender' => pb2bTenderResource::make($tender)->forSupplier()->resolve(),
            'items' => $this->serializeNoticeItems($tender),
            'documents' => pb2bTenderDocumentResource::collection($tender->getDocuments()),
            'criteria' => $this->serializeNoticeCriteria($tender),
            'application' => $application
                ? $this->serializeApplication($application, true)
                : null,
            'gates' => $this->gatePayload($application, $tender),
        );
    }

    /**
     * @return array{tender: array, application: array, gates: array}
     * @throws waException
     */
    public function getOrCreateDraft(int $tender_id, int $company_id): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::apply($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->statusService->ensureReceptionOpen($tender);
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);

        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if ($application) {
            if ($application->getStatusCode() === pb2bTenderApplication::STATUS_WITHDRAWN) {
                throw new waException('Заявка отозвана', pb2bHttpStatus::CONFLICT);
            }
            if (!pb2bTenderApplicationPolicy::view($application, $company)) {
                throw new waException('Заявка не доступна', pb2bHttpStatus::FORBIDDEN);
            }
        } else {
            $application = $this->createDraft($tender, $company);
        }

        return $this->supplierApplicationPayload($tender, $application);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array{tender: array, application: array, gates: array}
     * @throws waException
     */
    public function save(int $tender_id, int $company_id, array $payload): array
    {
        foreach (array('items', 'criteria', 'documents') as $field) {
            if (!array_key_exists($field, $payload)) continue;
            if ($payload[$field] === '') $payload[$field] = array();
            if (!is_array($payload[$field])) {
                throw new waException('Ожидается список: ' . $field, pb2bHttpStatus::BAD_REQUEST);
            }
        }
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::apply($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $model = new pb2bTenderApplicationModel();
        $model->query('START TRANSACTION');
        try {
            $this->statusService->ensureReceptionOpen($tender);
            $this->assertReceptionOpen($tender);
            $this->assertBeforeDeadline($tender);
            $application = $this->findApplication((int) $tender->id, (int) $company->id)
                ?? $this->createDraft($tender, $company);
            if (!pb2bTenderApplicationPolicy::update($application, $company)) {
                throw new waException('Заявка не доступна', pb2bHttpStatus::FORBIDDEN);
            }
            if (!$application->canEdit($tender)) {
                throw new waException('Заявку нельзя изменить в текущем состоянии', pb2bHttpStatus::CONFLICT);
            }
            if ($application->getStatusCode() === pb2bTenderApplication::STATUS_SUBMITTED) {
                $result = $application->applyRevertToDraft();
                if (!empty($result['error'])) {
                    throw new waException((string) ($result['message'] ?? 'Не удалось вернуть заявку в черновик'), pb2bHttpStatus::CONFLICT);
                }
            }
            if (array_key_exists('items', $payload) && !$this->canAccessProposal($application, $tender)) {
                throw new waException('Ценовое предложение откроется после прохождения обязательных проверок', pb2bHttpStatus::CONFLICT);
            }
            if (array_key_exists('items', $payload)) $this->saveItems($application, $tender, $payload['items']);
            if (array_key_exists('criteria', $payload)) $this->saveCriteria($application, $tender, $payload['criteria']);
            if (array_key_exists('documents', $payload)) $this->saveDocuments($application, $tender, $payload['documents']);

            $data = $application->data;
            $data['nonprice_done'] = $this->mandatoryCriteriaAnswered($application, $tender->getCriteria()) ? 1 : 0;
            $saved = $application->save($data);
            if (!empty($saved['error'])) {
                throw new waException((string) ($saved['message'] ?? 'Не удалось сохранить заявку'), pb2bHttpStatus::BAD_REQUEST);
            }
            $result = $this->supplierApplicationPayload($tender, $application);
            $model->query('COMMIT');

            return $result;
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    /**
     * @return array{tender: array, application: array, gates: array}
     * @throws waException
     */
    public function submit(int $tender_id, int $company_id): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::apply($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->statusService->ensureReceptionOpen($tender);
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);

        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if (!$application) {
            throw new waException('Сначала сохраните заявку', pb2bHttpStatus::CONFLICT);
        }
        if (!pb2bTenderApplicationPolicy::submit($application, $company)) {
            throw new waException('Заявка не доступна', pb2bHttpStatus::FORBIDDEN);
        }

        if (!$this->canAccessProposal($application, $tender)) {
            throw new waException('Не выполнены условия допуска к ценовому предложению', pb2bHttpStatus::CONFLICT);
        }
        $this->assertPricesComplete($application, $tender);
        $this->assertRequiredDocuments($application, $tender);

        if (!$application->canSubmit()) {
            throw new waException('Заявку нельзя подать в текущем статусе', pb2bHttpStatus::CONFLICT);
        }
        $result = $application->applySubmit();
        if (!empty($result['error'])) {
            throw new waException(
                (string) ($result['message'] ?? 'Не удалось подать заявку'),
                pb2bHttpStatus::CONFLICT
            );
        }

        return $this->supplierApplicationPayload($tender, $application);
    }

    /**
     * @return array{tender: array, application: array, gates: array}
     * @throws waException
     */
    public function withdraw(int $tender_id, int $company_id): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::apply($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->statusService->ensureReceptionOpen($tender);
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);

        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if (!$application) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        if (!pb2bTenderApplicationPolicy::withdraw($application, $company)) {
            throw new waException('Заявка не доступна', pb2bHttpStatus::FORBIDDEN);
        }

        if (!$application->canWithdraw($tender)) {
            throw new waException('Заявку нельзя отозвать в текущем статусе', pb2bHttpStatus::CONFLICT);
        }
        $result = $application->applyWithdraw();
        if (!empty($result['error'])) {
            throw new waException(
                (string) ($result['message'] ?? 'Не удалось отозвать заявку'),
                pb2bHttpStatus::CONFLICT
            );
        }

        return $this->supplierApplicationPayload($tender, $application);
    }

    /**
     * @return array{file_link_id: int, filename: string, size: int, ext: string}
     * @throws waException
     */
    public function uploadFile(int $tender_id, int $company_id, waRequestFile $upload_file): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);
        if (!pb2bTenderPolicy::apply($tender, $company, $has_invitation)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->statusService->ensureReceptionOpen($tender);
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);
        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if ($application && (!pb2bTenderApplicationPolicy::update($application, $company) || !$application->canEdit($tender))) {
            throw new waException('Заявку нельзя изменить в текущем состоянии', pb2bHttpStatus::CONFLICT);
        }
        $upload = $this->fileStorageService->validateDocumentUpload($upload_file);
        $file_link = $this->fileStorageService->saveFileAndCreateLink(
            $upload_file,
            self::FILE_DIR_NAME,
            (int) $company->id,
            array('purpose' => 'tender_application_upload', 'tender_id' => (int) $tender->id)
        );

        return array(
            'file_link_id' => (int) $file_link->id,
            'filename' => (string) $file_link->data['filename'],
            'size' => $upload['size'],
            'ext' => $upload['ext'],
        );
    }

    public function downloadCriterionFileForSupplier(
        int $tender_id,
        int $application_id,
        int $criterion_id,
        int $company_id
    ): void {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $application = $this->getApplicationWithAssert($application_id);
        if ((int) ($application->data['tender_id'] ?? 0) !== $tender_id
            || !pb2bTenderApplicationPolicy::view($application, $company)) {
            throw new waException('Файл критерия не найден', pb2bHttpStatus::NOT_FOUND);
        }
        $this->sendCriterionFile($application, $criterion_id);
    }

    public function downloadCriterionFileForBuyer(
        int $tender_id,
        int $application_id,
        int $criterion_id,
        int $company_id
    ): void {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $application = $this->getApplicationWithAssert($application_id);
        if ((int) ($application->data['tender_id'] ?? 0) !== $tender_id
            || !pb2bTenderApplicationPolicy::viewAsBuyer($application, $company)) {
            throw new waException('Файл критерия не найден', pb2bHttpStatus::NOT_FOUND);
        }
        $this->sendCriterionFile($application, $criterion_id);
    }

    public function downloadDocumentFileForBuyer(
        int $tender_id,
        int $application_id,
        int $document_id,
        pb2bCompany $company
    ): void {
        $tender = $this->getTenderWithAssert($tender_id);
        $application = $this->getApplicationWithAssert($application_id);
        if (!pb2bTenderPolicy::view($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        if ((int) $application->data['tender_id'] !== (int) $tender->id) {
            throw new waException('Документ заявки не найден', pb2bHttpStatus::NOT_FOUND);
        }
        foreach ($application->getDocuments() as $document) {
            if ((int) $document['id'] === $document_id) {
                $this->sendApplicationFile($application, (int) ($document['file_link_id'] ?? 0));
                return;
            }
        }
        throw new waException('Документ заявки не найден', pb2bHttpStatus::NOT_FOUND);
    }

    private function sendCriterionFile(pb2bTenderApplication $application, int $criterion_id): void
    {
        $tender_id = (int) ($application->data['tender_id'] ?? 0);
        $criterion = null;
        foreach ((new pb2bTenderCriterionCollection())->getByTenderId($tender_id) as $row) {
            if ((int) ($row['id'] ?? 0) === $criterion_id) {
                $criterion = $row;
                break;
            }
        }
        if (!$criterion || $this->criterionAnswerType($criterion) !== 'file') {
            throw new waException('Файл критерия не найден', pb2bHttpStatus::NOT_FOUND);
        }
        $answers = array_column($application->getCriteria(), null, 'criterion_id');
        $answer = $answers[$criterion_id] ?? array();
        $this->sendApplicationFile($application, (int) ($answer['file_link_id'] ?? 0));
    }

    private function sendApplicationFile(pb2bTenderApplication $application, int $file_link_id): void
    {
        $file_link = new pb2bFileLink($file_link_id);
        $file = $file_link->id ? $file_link->getFile() : null;
        if (!$file || (int) ($file_link->data['owner_company_id'] ?? 0)
            !== (int) ($application->data['supplier_company_id'] ?? 0)) {
            throw new waException('Файл заявки не найден', pb2bHttpStatus::NOT_FOUND);
        }
        $path = pb2bStorage::disk($file->data['storage_disk'])->path($file->data['storage_path']);
        if (!is_file($path)) {
            throw new waException('Файл заявки не найден', pb2bHttpStatus::NOT_FOUND);
        }
        waFiles::readFile($path, $file_link->data['filename']);
    }

    /**
     * @return array{tender: array, applications: list<array>}
     * @throws waException
     */
    public function getForBuyer(int $tender_id, int $company_id, int $application_id = 0): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::view($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->statusService->ensureReceptionOpen($tender);

        $include_prices = pb2bTenderStatus::isBuyerPriceVisible((pb2bTenderStatus::tryFromId((int) $tender->data['status'])?->value ?? ''));
        $collection = new pb2bTenderApplicationCollection('tender_id=' . (int) $tender->id
            . ($application_id > 0 ? '&id=' . $application_id : ''));
        $rows = $collection->getCollection(array('includes' => array('counts', 'supplier')));
        if ($application_id > 0 && !$rows) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        $applications = array();
        foreach ($rows as $row) {
            $application = new pb2bTenderApplication((int) $row['id']);
            $application_prices_visible = $include_prices
                && $application->getStatusCode() === pb2bTenderApplication::STATUS_SUBMITTED;
            $payload = $this->serializeApplication($application, $application_prices_visible, $application_id > 0, true, $row);
            if ($application_id > 0) {
                $payload = $this->enrichBuyerApplicationDetail($payload, $tender, $application);
            }
            $applications[] = $payload;
        }

        $counts_by_tender = (new pb2bTenderApplicationCollection())->applicationCountsByTenderIds(array((int) $tender->id));
        $counts = $counts_by_tender[(int) $tender->id];
        $tender_payload = pb2bTenderResource::make($tender)->additional($counts)->resolve();
        $tender_payload['participants_count'] = array_sum($counts);
        $tender_payload['proposals_count'] = $counts[pb2bTenderApplication::STATUS_SUBMITTED];
        $payload = array(
            'tender' => $tender_payload,
            'applications' => $applications,
            'prices_visible' => $include_prices ? 1 : 0,
        );
        if ($application_id > 0) {
            $payload['application'] = $applications[0];
        }

        return $payload;
    }

    /**
     * Решения организатора по гейтам заявки (approval / qualification / admission).
     * Статус тендера не меняет. Идемпотентность: повтор того же кода при уже выставленном — 200.
     *
     * @param array{
     *   approval_status?: string|null,
     *   qualification_status?: string|null,
     *   admission_status?: string|null,
     *   comment?: string|null,
     *   approval_comment?: string|null,
     *   qualification_comment?: string|null,
     *   admission_comment?: string|null
     * } $payload
     * @return array{tender: array, application: array, prices_visible: int}
     * @throws waException
     */
    public function decideForBuyer(int $tender_id, int $company_id, int $application_id, array $payload): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::view($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }

        $application = $this->getApplicationWithAssert($application_id);
        $row = $application->data;
        if ((int) ($row['tender_id'] ?? 0) !== (int) $tender->id) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        if (!pb2bTenderApplicationPolicy::decideAsBuyer($application, $company)) {
            throw new waException('Заявка не доступна', pb2bHttpStatus::FORBIDDEN);
        }
        $approval_status = $this->optionalDecisionCode($payload, 'approval_status');
        $qualification_status = $this->optionalDecisionCode($payload, 'qualification_status');
        $admission_status = $this->optionalDecisionCode($payload, 'admission_status');
        if ($approval_status === null && $qualification_status === null && $admission_status === null) {
            throw new waException(
                'Укажите хотя бы одно решение: approval_status, qualification_status или admission_status',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $status = $application->getStatusCode();
        $has_preliminary_decision = $approval_status !== null || $qualification_status !== null;
        if ($has_preliminary_decision && $admission_status !== null) {
            throw new waException(
                'Предварительные решения и допуск заявки выполняются отдельно',
                pb2bHttpStatus::BAD_REQUEST
            );
        }
        if ($has_preliminary_decision) {
            $this->assertReceptionOpen($tender);
            $this->assertBeforeDeadline($tender);
            if ($status !== pb2bTenderApplication::STATUS_DRAFT) {
                throw new waException(
                    'Одобрение и квалификация выполняются до подачи предложения',
                    pb2bHttpStatus::CONFLICT
                );
            }
            if (!$this->mandatoryCriteriaAnswered($application, $tender->getCriteria())) {
                throw new waException(
                    'Сначала поставщик должен заполнить обязательные неценовые критерии',
                    pb2bHttpStatus::CONFLICT
                );
            }
        }
        if ($admission_status !== null && $status !== pb2bTenderApplication::STATUS_SUBMITTED) {
            throw new waException(
                'Допуск можно выполнить только по поданному предложению',
                pb2bHttpStatus::CONFLICT
            );
        }

        $shared_comment = trim((string) ($payload['comment'] ?? ''));
        $data = $row;
        $touched = false;

        if ($approval_status !== null) {
            $touched = $this->applyApprovalDecision(
                $data,
                $tender,
                $approval_status,
                $shared_comment,
                isset($payload['approval_comment']) ? trim((string) $payload['approval_comment']) : null
            ) || $touched;
        }
        if ($qualification_status !== null) {
            $touched = $this->applyQualificationDecision(
                $data,
                $qualification_status,
                $shared_comment,
                isset($payload['qualification_comment']) ? trim((string) $payload['qualification_comment']) : null
            ) || $touched;
        }
        if ($admission_status !== null) {
            $touched = $this->applyAdmissionDecision(
                $data,
                $admission_status,
                $shared_comment,
                isset($payload['admission_comment']) ? trim((string) $payload['admission_comment']) : null
            ) || $touched;
        }

        if ($touched) {
            $result = $application->save($data);
            if (!empty($result['error'])) {
                throw new waException(
                    (string) ($result['message'] ?? 'Не удалось сохранить решение'),
                    pb2bHttpStatus::BAD_REQUEST
                );
            }
        }

        return $this->getForBuyer($tender_id, $company_id, $application_id);
    }

    /**
     * Сравнение КП для организатора после вскрытия (на лету, без persist ранга).
     *
     * @return array{
     *   tender_id:int,
     *   status:string,
     *   itemized_enabled:int,
     *   currency:string,
     *   applications:list<array<string,mixed>>
     * }
     * @throws waException
     */
    public function rankingForBuyer(int $tender_id, int $company_id): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::view($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }

        $status_code = (pb2bTenderStatus::tryFromId((int) $tender->data['status'])?->value ?? '');
        if (!pb2bTenderStatus::isBuyerPriceVisible($status_code)) {
            throw new waException(
                'Сначала начните рассмотрение заявок',
                pb2bHttpStatus::CONFLICT
            );
        }

        $tender_row = $tender->data;
        $itemized = !empty($tender_row['itemized_enabled']) ? 1 : 0;
        $currency = trim((string) ($tender_row['currency'] ?? 'RUB'));
        if ($currency === '') {
            $currency = 'RUB';
        }

        $notice_items = array();
        foreach ($tender->getItems() as $item) {
            if (!is_array($item)) {
                continue;
            }
            $tid = (int) ($item['id'] ?? 0);
            if ($tid <= 0) {
                continue;
            }
            $notice_items[$tid] = array(
                'tender_item_id' => $tid,
                'name' => (string) ($item['name'] ?? ''),
                'qty' => $item['qty'] ?? null,
                'unit' => $item['unit'] ?? null,
                'vat_rate' => $item['vat_rate'] ?? null,
                'sort' => (int) ($item['sort'] ?? 0),
            );
        }

        $candidates = array();
        foreach ($this->eligibleApplicationsForBuyer($tender, $company) as $eligible) {
            $built = $this->buildRankingCandidate(
                $eligible['application'],
                $eligible['row'],
                $notice_items,
                $eligible['admission_status']
            );
            if ($built === null) {
                continue;
            }
            $candidates[] = $built;
        }

        $documents = array();
        $criteria = array();
        $application_ids = array_column($candidates, 'application_id');
        foreach ((new pb2bTenderApplicationDocumentCollection())->getByApplicationIds($application_ids) as $document) {
            $documents[(int) $document['application_id']][] = $document;
        }
        foreach ((new pb2bTenderApplicationCriterionCollection())->getByApplicationIds($application_ids) as $answer) {
            $criteria[(int) $answer['application_id']][] = $answer;
        }
        foreach ($candidates as &$candidate) {
            $id = (int) $candidate['application_id'];
            $candidate['comparison'] = array(
                'items' => $candidate['items'],
                'totals' => $this->comparisonTotals($candidate['items']),
                'documents' => pb2bTenderApplicationDocumentResource::collection($documents[$id] ?? array()),
                'criteria' => pb2bTenderApplicationCriterionResource::collection($criteria[$id] ?? array()),
            );
        }
        unset($candidate);

        usort($candidates, array($this, 'compareRankingByTotal'));
        $this->assignCompetitionRanks($candidates, 'total', 'rank');

        if ($itemized) {
            $this->assignItemizedRanks($candidates, $notice_items);
        } else {
            foreach ($candidates as &$candidate) {
                unset($candidate['items']);
            }
            unset($candidate);
        }

        usort($candidates, array($this, 'compareRankingByRank'));

        return array(
            'tender_id' => (int) $tender->id,
            'status' => $status_code,
            'itemized_enabled' => $itemized,
            'currency' => $currency,
            'applications' => $candidates,
            'comparison' => array(
                'items' => array_values($notice_items),
                'documents' => pb2bTenderDocumentResource::collection($tender->getDocuments(pb2bTenderDocument::KIND_REQUIREMENT)),
                'criteria' => $this->serializeNoticeCriteria($tender),
            ),
        );
    }

    /**
     * Состояние черновика выбора победителя для организатора.
     *
     * @return array<string, mixed>
     * @throws waException
     */
    public function getWinnerStateForBuyer(int $tender_id, int $company_id): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::view($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->assertWinnerReviewStarted($tender);

        return $this->buildWinnerState($tender, $company);
    }

    /**
     * Режим all: один победитель на всю процедуру.
     *
     * @return array<string, mixed>
     * @throws waException
     */
    public function selectWinnerAll(int $tender_id, int $company_id, int $application_id): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::selectWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->assertWinnerPriceRequest($tender);
        $this->assertWinnerDraftEditable($tender);

        if ($application_id <= 0) {
            throw new waException('Не указана заявка', pb2bHttpStatus::BAD_REQUEST);
        }
        $application = $this->getApplicationWithAssert($application_id);
        if (!$this->isEligibleForWinnerSelection($application, $company, (int) $tender->id)) {
            throw new waException('Заявка не участвует в выборе победителя', pb2bHttpStatus::CONFLICT);
        }

        $mode_id = pb2bTender::winnerSelectionModeCodeToId('all');
        if ($mode_id <= 0) {
            throw new waException('Режим выбора победителя не настроен', pb2bHttpStatus::BAD_REQUEST);
        }

        $model = new pb2bTenderApplicationModel();
        $model->query('START TRANSACTION');
        try {
            $this->clearAllWinnerFlags($tender);
            $row = $application->data;
            unset($row['create_datetime'], $row['update_datetime']);
            $row['is_winner'] = 1;
            $row['_winner_via_service'] = 1;
            $saved = $application->save($row);
            if (!empty($saved['error'])) {
                throw new waException(
                    (string) ($saved['message'] ?? 'Не удалось сохранить победителя'),
                    pb2bHttpStatus::BAD_REQUEST
                );
            }

            $this->saveTenderWinnerDraftFields($tender, array(
                'winner_selection_mode' => $mode_id,
                'finished_without_winner' => 0,
                'finished_without_winner_reason' => null,
            ));

            $state = $this->buildWinnerState($tender, $company);
            $model->query('COMMIT');

            return $state;
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    /**
     * Режим positional: победитель по позициям (только itemized).
     *
     * @param list<array{tender_item_id?: mixed, application_id?: mixed}> $items
     * @return array<string, mixed>
     * @throws waException
     */
    public function selectWinnerPositional(int $tender_id, int $company_id, array $items): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::selectWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->assertWinnerPriceRequest($tender);
        $this->assertWinnerDraftEditable($tender);
        $tender_row = $tender->data;
        if (empty($tender_row['itemized_enabled'])) {
            throw new waException(
                'Попозиционный выбор доступен только при включённой попозиционной закупке',
                pb2bHttpStatus::CONFLICT
            );
        }

        $notice_ids = array();
        $notice_names = array();
        foreach ($tender->getItems() as $item) {
            if (!is_array($item)) {
                continue;
            }
            $tid = (int) ($item['id'] ?? 0);
            if ($tid <= 0) {
                continue;
            }
            $notice_ids[$tid] = true;
            $notice_names[$tid] = (string) ($item['name'] ?? '');
        }
        if ($notice_ids === array()) {
            throw new waException('У тендера нет позиций для выбора', pb2bHttpStatus::CONFLICT);
        }

        $map = array();
        foreach ($items as $row) {
            if (!is_array($row)) {
                continue;
            }
            $tid = (int) ($row['tender_item_id'] ?? 0);
            $aid = (int) ($row['application_id'] ?? 0);
            if ($tid <= 0 || $aid <= 0) {
                throw new waException('Укажите tender_item_id и application_id', pb2bHttpStatus::BAD_REQUEST);
            }
            if (!isset($notice_ids[$tid])) {
                throw new waException('Позиция извещения не найдена', pb2bHttpStatus::BAD_REQUEST);
            }
            if (isset($map[$tid])) {
                throw new waException('Дубликат позиции в выборе победителя', pb2bHttpStatus::BAD_REQUEST);
            }
            $map[$tid] = $aid;
        }

        $eligible_by_id = array();
        foreach ($this->eligibleApplicationsForBuyer($tender, $company) as $eligible) {
            $aid = (int) $eligible['application']->id;
            $eligible_by_id[$aid] = $eligible['application'];
        }

        $application_items = array();
        foreach ($map as $tid => $aid) {
            if (!isset($eligible_by_id[$aid])) {
                throw new waException(
                    'Заявка не участвует в выборе победителя',
                    pb2bHttpStatus::CONFLICT
                );
            }
            if (!isset($application_items[$aid])) {
                $application_items[$aid] = array_column($eligible_by_id[$aid]->getItems(), null, 'tender_item_id');
            }
            if (!isset($application_items[$aid][$tid])) {
                throw new waException(
                    'У заявки нет цены по позиции «' . ($notice_names[$tid] !== '' ? $notice_names[$tid] : $tid) . '»',
                    pb2bHttpStatus::CONFLICT
                );
            }
        }

        $mode_id = pb2bTender::winnerSelectionModeCodeToId('positional');
        if ($mode_id <= 0) {
            throw new waException('Режим выбора победителя не настроен', pb2bHttpStatus::BAD_REQUEST);
        }

        $model = new pb2bTenderApplicationModel();
        $model->query('START TRANSACTION');
        try {
            $this->clearAllWinnerFlags($tender);

            foreach ($map as $tid => $aid) {
                $item_row = $application_items[$aid][$tid];
                $item_id = (int) ($item_row['id'] ?? 0);
                $object = new pb2bTenderApplicationItem($item_id > 0 ? $item_id : null);
                $save_row = $item_row;
                unset($save_row['create_datetime'], $save_row['update_datetime']);
                $save_row['is_winner'] = 1;
                $save_row['_winner_via_service'] = 1;
                $saved = $object->save($save_row);
                if (!empty($saved['error'])) {
                    throw new waException(
                        (string) ($saved['message'] ?? 'Не удалось сохранить победителя позиции'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
            }

            $this->saveTenderWinnerDraftFields($tender, array(
                'winner_selection_mode' => $mode_id,
                'finished_without_winner' => 0,
                'finished_without_winner_reason' => null,
            ));

            $state = $this->buildWinnerState($tender, $company);
            $model->query('COMMIT');

            return $state;
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    /**
     * Сброс черновика выбора победителя.
     *
     * @return array<string, mixed>
     * @throws waException
     */
    public function clearWinnerDraft(int $tender_id, int $company_id): array
    {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::selectWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->assertWinnerPriceRequest($tender);
        $this->assertWinnerDraftEditable($tender);

        $mode_id = pb2bTender::winnerSelectionModeCodeToId('none');
        if ($mode_id <= 0) {
            $mode_id = 1;
        }

        $model = new pb2bTenderApplicationModel();
        $model->query('START TRANSACTION');
        try {
            $this->clearAllWinnerFlags($tender);
            $this->saveTenderWinnerDraftFields($tender, array(
                'winner_selection_mode' => $mode_id,
                'finished_without_winner' => 0,
                'finished_without_winner_reason' => null,
            ));

            $state = $this->buildWinnerState($tender, $company);
            $model->query('COMMIT');

            return $state;
        } catch (Throwable $e) {
            $model->query('ROLLBACK');
            throw $e;
        }
    }

    /**
     * Перед finish-without: сброс awards + mode=none + finished_without + reason.
     * Статус тендера не меняет (это TenderService).
     *
     * @throws waException
     */
    public function applyFinishWithoutWinnerDraft(int $tender_id, int $company_id, string $reason): void
    {
        $reason = trim($reason);
        if ($reason === '') {
            throw new waException(
                'Укажите причину завершения без победителя',
                pb2bHttpStatus::BAD_REQUEST
            );
        }
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::selectWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $this->assertWinnerPriceRequest($tender);
        $this->assertWinnerDraftEditable($tender);

        $mode_id = pb2bTender::winnerSelectionModeCodeToId('none');
        if ($mode_id <= 0) {
            $mode_id = 1;
        }

        $this->clearAllWinnerFlags($tender);
        $this->saveTenderWinnerDraftFields($tender, array(
            'winner_selection_mode' => $mode_id,
            'finished_without_winner' => 1,
            'finished_without_winner_reason' => $reason,
        ));
    }

    /**
     * После перехода в summarizing_results: зафиксировать completed.
     *
     * @throws waException
     */
    public function markWinnerSelectionCompleted(
        int $tender_id,
        int $company_id,
        bool $finished_without_winner
    ): void {
        $company = $this->getBuyerCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        if (!pb2bTenderPolicy::completeWinner($tender, $company)) {
            throw new waException('Тендер не доступен', pb2bHttpStatus::FORBIDDEN);
        }
        $data = $tender->data;
        $data['winner_selection_completed'] = 1;
        if (!$finished_without_winner) {
            $data['finished_without_winner'] = 0;
            $data['finished_without_winner_reason'] = null;
        } else {
            $data['finished_without_winner'] = 1;
        }
        $this->saveTenderWinnerDraftFields($tender, array(
            'winner_selection_completed' => (int) $data['winner_selection_completed'],
            'finished_without_winner' => (int) $data['finished_without_winner'],
            'finished_without_winner_reason' => $data['finished_without_winner_reason'],
        ));
    }

    public function canAccessProposal(?pb2bTenderApplication $application, pb2bTender $tender): bool
    {
        return $this->gatePayload($application, $tender)['proposal'];
    }

    /**
     * Stepper участия: состояния шагов и next_required — только по гейтам Service.
     * JS не пересчитывает правила, только рисует и ходит по URL.
     *
     * @return array{
     *   gates: array,
     *   next_required_step: string,
     *   current_step: string,
     *   requested_step: string,
     *   redirected: bool,
     *   stages: list<array{id: string, label: string, state: string, accessible: bool}>,
     *   approval_status: string,
     *   qualification_status: string
     * }
     */
    public function resolveParticipationFlow(
        ?pb2bTenderApplication $application,
        pb2bTender $tender,
        string $requested_step = ''
    ): array {
        $gates = $this->gatePayload($application, $tender);
        $approval_status = (string) ($gates['approval_status'] ?? 'not_required');
        $qualification_status = (string) ($gates['qualification_status'] ?? 'not_required');

        $next = $this->nextRequiredParticipationStep($gates);
        $requested = pb2bTenderParticipationStep::tryParse($requested_step);
        $requested_step = $requested ? $requested->value : '';

        $approval_needed = !empty($gates['approval_required']) && $approval_status !== 'not_required';
        $qualification_needed = !empty($gates['qualification_required'])
            && $qualification_status !== 'not_required';

        $accessible = array(
            pb2bTenderParticipationStep::NON_PRICE->value => !empty($gates['nonprice_present']),
            pb2bTenderParticipationStep::APPROVAL->value => (
                empty($gates['nonprice_required']) || !empty($gates['nonprice_done'])
            ) && $approval_needed,
            pb2bTenderParticipationStep::QUALIFICATION->value => (
                empty($gates['nonprice_required']) || !empty($gates['nonprice_done'])
            ) && $qualification_needed,
            pb2bTenderParticipationStep::PROPOSAL->value => !empty($gates['proposal']),
        );

        $redirected = false;
        if ($requested_step === '') {
            $current = $next;
        } elseif (empty($accessible[$requested_step])) {
            $current = $next;
            $redirected = true;
        } else {
            $current = $requested_step;
        }

        // Пропущенные (не требуются) этапы не показываем в степпере.
        $stages = array();
        foreach (pb2bTenderParticipationStep::ordered() as $step) {
            $id = $step->value;
            $state = $this->participationStageState(
                $id,
                $gates,
                $approval_status,
                $qualification_status,
                $current
            );
            if ($state === 'skipped') {
                continue;
            }
            $stages[] = array(
                'id' => $id,
                'label' => $step->name(),
                'state' => $state,
                'accessible' => !empty($accessible[$id]),
            );
        }

        return array(
            'gates' => $gates,
            'next_required_step' => $next,
            'current_step' => $current,
            'requested_step' => $requested_step,
            'redirected' => $redirected,
            'stages' => $stages,
            'approval_status' => $approval_status,
            'qualification_status' => $qualification_status,
        );
    }

    /**
     * @param array{nonprice_done?: bool, approval_ok?: bool, qualification_ok?: bool, proposal?: bool} $gates
     */
    private function nextRequiredParticipationStep(array $gates): string
    {
        if (!empty($gates['nonprice_present'])
            && !empty($gates['nonprice_required'])
            && empty($gates['nonprice_done'])
        ) {
            return pb2bTenderParticipationStep::NON_PRICE->value;
        }
        if (empty($gates['approval_ok'])) {
            return pb2bTenderParticipationStep::APPROVAL->value;
        }
        if (empty($gates['qualification_ok'])) {
            return pb2bTenderParticipationStep::QUALIFICATION->value;
        }

        return pb2bTenderParticipationStep::PROPOSAL->value;
    }

    /**
     * @param array{
     *   nonprice_present?: bool,
     *   nonprice_done?: bool,
     *   nonprice_required?: bool,
     *   approval_ok?: bool,
     *   approval_required?: bool,
     *   qualification_ok?: bool,
     *   qualification_required?: bool,
     *   proposal?: bool
     * } $gates
     */
    private function participationStageState(
        string $id,
        array $gates,
        string $approval_status,
        string $qualification_status,
        string $current
    ): string {
        if ($id === pb2bTenderParticipationStep::NON_PRICE->value) {
            if (empty($gates['nonprice_present'])) {
                return 'skipped';
            }
            // Необязательный шаг отображается, но не блокирует следующие этапы.
            // После сохранения ответов он становится completed.
            if (!empty($gates['nonprice_done'])) {
                return $current === pb2bTenderParticipationStep::NON_PRICE->value
                    ? 'active'
                    : 'completed';
            }

            return $current === pb2bTenderParticipationStep::NON_PRICE->value ? 'active' : 'available';
        }

        if ($id === pb2bTenderParticipationStep::APPROVAL->value) {
            if (empty($gates['nonprice_done']) && !empty($gates['nonprice_required'])) {
                return 'locked';
            }
            if (empty($gates['approval_required']) || $approval_status === 'not_required') {
                return 'skipped';
            }
            if ($approval_status === 'approved') {
                return 'completed';
            }
            if ($approval_status === 'pending') {
                return $current === pb2bTenderParticipationStep::APPROVAL->value ? 'active' : 'waiting';
            }
            if ($approval_status === 'rejected') {
                return 'blocked';
            }

            return $current === pb2bTenderParticipationStep::APPROVAL->value ? 'active' : 'available';
        }

        if ($id === pb2bTenderParticipationStep::QUALIFICATION->value) {
            if (empty($gates['nonprice_done']) && !empty($gates['nonprice_required'])) {
                return 'locked';
            }
            if (empty($gates['qualification_required']) || $qualification_status === 'not_required') {
                return 'skipped';
            }
            if ($qualification_status === 'passed') {
                return 'completed';
            }
            if ($qualification_status === 'pending') {
                return $current === pb2bTenderParticipationStep::QUALIFICATION->value ? 'active' : 'waiting';
            }
            if ($qualification_status === 'failed') {
                return 'blocked';
            }

            return $current === pb2bTenderParticipationStep::QUALIFICATION->value ? 'active' : 'available';
        }

        // proposal
        if (empty($gates['proposal'])) {
            return 'locked';
        }

        return $current === pb2bTenderParticipationStep::PROPOSAL->value ? 'active' : 'available';
    }

    private function getSupplierCompanyWithAssert(int $company_id): pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        if (!$company->id || !$company->isSupplier()) {
            throw new waException('Не найдена компания-поставщик', pb2bHttpStatus::NOT_FOUND);
        }

        return $company;
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
        if ($tender_id <= 0) {
            throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        }

        $tender = new pb2bTender($tender_id);
        if (!$tender->id) {
            throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        }

        return $tender;
    }

    private function getApplicationWithAssert(int $application_id): pb2bTenderApplication
    {
        $application = new pb2bTenderApplication($application_id);
        if (!(int) $application->id) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }

        return $application;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function optionalDecisionCode(array $payload, string $key): ?string
    {
        if (!array_key_exists($key, $payload) || $payload[$key] === null || $payload[$key] === '') {
            return null;
        }

        return trim((string) $payload[$key]);
    }

    /**
     * @param array<string, mixed> $data
     * @return bool true если нужно сохранить (статус реально меняется)
     * @throws waException
     */
    private function applyApprovalDecision(
        array &$data,
        pb2bTender $tender,
        string $new_code,
        string $shared_comment,
        ?string $specific_comment
    ): bool {
        $approval_required = !empty($tender->data['approval_required']);
        $current = trim((string) ($data['approval_status'] ?? ''));
        if (!$approval_required || $current === 'not_required') {
            throw new waException(
                'Одобрение не требуется для этого тендера',
                pb2bHttpStatus::CONFLICT
            );
        }
        if ($current === $new_code) {
            return false;
        }
        if ($current !== 'pending') {
            throw new waException('По одобрению решение уже принято', pb2bHttpStatus::CONFLICT);
        }
        if (!in_array($new_code, array('approved', 'rejected'), true)) {
            throw new waException('Недопустимый статус одобрения', pb2bHttpStatus::BAD_REQUEST);
        }
        $comment = ($specific_comment !== null && $specific_comment !== '' ? $specific_comment : $shared_comment);
        if ($new_code === 'rejected' && $comment === '') {
            throw new waException('Укажите причину отклонения одобрения', pb2bHttpStatus::CONFLICT);
        }
        $data['approval_status'] = $new_code;
        if ($comment !== '') {
            $data['approval_comment'] = $comment;
        }

        return true;
    }

    /**
     * @param array<string, mixed> $data
     * @throws waException
     */
    private function applyQualificationDecision(
        array &$data,
        string $new_code,
        string $shared_comment,
        ?string $specific_comment
    ): bool {
        $current = trim((string) ($data['qualification_status'] ?? ''));
        if ($current === 'not_required' || $current === '') {
            throw new waException(
                'Квалификация не требуется для этой заявки',
                pb2bHttpStatus::CONFLICT
            );
        }
        if ($current === $new_code) {
            return false;
        }
        if ($current !== 'pending') {
            throw new waException('По квалификации решение уже принято', pb2bHttpStatus::CONFLICT);
        }
        if (!in_array($new_code, array('passed', 'failed'), true)) {
            throw new waException('Недопустимый статус квалификации', pb2bHttpStatus::BAD_REQUEST);
        }
        $comment = ($specific_comment !== null && $specific_comment !== '' ? $specific_comment : $shared_comment);
        if ($new_code === 'failed' && $comment === '') {
            throw new waException('Укажите причину отказа в квалификации', pb2bHttpStatus::CONFLICT);
        }
        $data['qualification_status'] = $new_code;
        if ($comment !== '') {
            $data['qualification_comment'] = $comment;
        }

        return true;
    }

    /**
     * @param array<string, mixed> $data
     * @throws waException
     */
    private function applyAdmissionDecision(
        array &$data,
        string $new_code,
        string $shared_comment,
        ?string $specific_comment
    ): bool {
        $current = trim((string) ($data['admission_status'] ?? ''));
        if ($current === $new_code) {
            return false;
        }
        if ($current !== 'pending') {
            throw new waException('По допуску решение уже принято', pb2bHttpStatus::CONFLICT);
        }
        if (!in_array($new_code, array('admitted', 'rejected'), true)) {
            throw new waException('Недопустимый статус допуска', pb2bHttpStatus::BAD_REQUEST);
        }
        $comment = ($specific_comment !== null && $specific_comment !== '' ? $specific_comment : $shared_comment);
        if ($new_code === 'rejected' && $comment === '') {
            throw new waException('Укажите причину отказа в допуске', pb2bHttpStatus::CONFLICT);
        }
        $data['admission_status'] = $new_code;
        if ($comment !== '') {
            $data['admission_comment'] = $comment;
        }

        return true;
    }

    /**
     * @return array{
     *   approval: array{can_decide: int},
     *   qualification: array{can_decide: int},
     *   admission: array{can_decide: int}
     * }
     */
    private function buyerGateActions(pb2bTenderApplication $application, pb2bTender $tender): array
    {
        $row = $application->data;
        $draft = $application->getStatusCode() === pb2bTenderApplication::STATUS_DRAFT
            && pb2bTenderStatus::tryFromId((int) $tender->data['status']) === pb2bTenderStatus::ACCEPTING_APPLICATIONS
            && !$tender->isReceptionDeadlinePassed();
        $submitted = $application->getStatusCode() === pb2bTenderApplication::STATUS_SUBMITTED;
        $approval_required = !empty($tender->data['approval_required']);
        $nonprice_ready = $this->mandatoryCriteriaAnswered($application, $tender->getCriteria());
        $approval = trim((string) ($row['approval_status'] ?? ''));
        $qualification = trim((string) ($row['qualification_status'] ?? ''));
        $admission = trim((string) ($row['admission_status'] ?? ''));

        return array(
            'approval' => array(
                'can_decide' => ($draft && $nonprice_ready && $approval_required && $approval === 'pending') ? 1 : 0,
            ),
            'qualification' => array(
                'can_decide' => ($draft && $nonprice_ready && $qualification === 'pending') ? 1 : 0,
            ),
            'admission' => array(
                'can_decide' => ($submitted && $admission === 'pending') ? 1 : 0,
            ),
        );
    }

    /**
     * Поля карточки списка, которых нет в общем resource тендера.
     *
     * @return array{
     *   organizer: string,
     *   city: string,
     *   category: string,
     *   mnn: list<string>,
     *   requires_prequalification: int
     * }
     */
    private function supplierListCard(pb2bTender $tender): array
    {
        $row = $tender->data;
        $organizer = '';
        $organizer_id = (int) ($row['organizer_company_id'] ?? 0);
        if ($organizer_id > 0) {
            $company = new pb2bCompany($organizer_id);
            if ((int) $company->id) {
                $organizer = trim($company->getFullName());
            }
        }

        $mnn = array();
        $classifier_rows = $tender->getClassifiers();
        $classifiers = pb2bTenderClassifierResource::collection($classifier_rows);
        $summary = pb2bTenderSummaryHelper::supplierNotice($tender->getItems(), $classifier_rows);
        foreach ($classifiers as $classifier) {
            if (!is_array($classifier)) {
                continue;
            }
            $name = trim((string) ($classifier['classifier_name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $code = (string) ($classifier['classifier_type_code'] ?? '');
            if ($code === 'esklp' && count($mnn) < 2) {
                $mnn[] = $name;
            }
        }

        return array(
            'organizer' => $organizer,
            'city' => $summary['place'],
            'category' => $summary['category'],
            'mnn' => array_values($mnn),
            'requires_prequalification' => (int) ($row['past_prequal_tender_id'] ?? 0) > 0 ? 1 : 0,
        );
    }

    private function assertReceptionOpen(pb2bTender $tender): void
    {
        if (pb2bTenderStatus::tryFromId((int) $tender->data['status']) !== pb2bTenderStatus::ACCEPTING_APPLICATIONS) {
            throw new waException('Приём заявок закрыт', pb2bHttpStatus::CONFLICT);
        }
    }

    private function assertBeforeDeadline(pb2bTender $tender): void
    {
        if ($tender->isReceptionDeadlinePassed()) {
            throw new waException('Срок подачи заявок истёк', pb2bHttpStatus::CONFLICT);
        }
    }

    public function canEdit(pb2bTenderApplication $application, pb2bTender $tender, pb2bCompany $company): bool
    {
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);

        return pb2bTenderPolicy::apply($tender, $company, $has_invitation)
            && pb2bTenderApplicationPolicy::update($application, $company)
            && $application->canEdit($tender);
    }

    public function canWithdraw(pb2bTenderApplication $application, pb2bTender $tender, pb2bCompany $company): bool
    {
        $has_invitation = !empty($tender->data['is_private'])
            && (new pb2bTenderInvitationCollection())->existsForTenderAndCompany((int) $tender->id, (int) $company->id);

        return pb2bTenderPolicy::apply($tender, $company, $has_invitation)
            && pb2bTenderApplicationPolicy::withdraw($application, $company)
            && $application->canWithdraw($tender);
    }

    private function findApplication(int $tender_id, int $supplier_company_id): ?pb2bTenderApplication
    {
        if ($tender_id <= 0 || $supplier_company_id <= 0) {
            return null;
        }
        $rows = (new pb2bTenderApplicationCollection('tender_id=' . $tender_id . '&supplier_company_id=' . $supplier_company_id))
            ->getCollection(array('select' => array('id' => null), 'limit' => 1));
        $row = reset($rows);

        return $row ? new pb2bTenderApplication((int) $row['id']) : null;
    }

    private function createDraft(pb2bTender $tender, pb2bCompany $company): pb2bTenderApplication
    {
        $has_any_criteria = (bool) $tender->getCriteria();
        $tender_row = $tender->data;
        $approval_required = !empty($tender_row['approval_required']);
        $qualification_required = (int) ($tender_row['past_prequal_tender_id'] ?? 0) > 0;
        $application = new pb2bTenderApplication();
        $save = $application->save(array(
            'tender_id' => (int) $tender->id,
            'supplier_company_id' => (int) $company->id,
            'status' => pb2bTenderApplication::STATUS_DRAFT,
            'nonprice_done' => $has_any_criteria ? 0 : 1,
            'approval_status' => $approval_required ? 'pending' : 'not_required',
            'qualification_status' => $qualification_required ? 'pending' : 'not_required',
            'admission_status' => 'pending',
        ));
        if (!empty($save['error']) || !(int) $application->id) {
            throw new waException(
                (string) ($save['message'] ?? 'Не удалось создать заявку'),
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        return $application;
    }

    /**
     * @return array{tender: array, application: array, gates: array}
     */
    private function supplierApplicationPayload(pb2bTender $tender, pb2bTenderApplication $application): array
    {
        return array(
            'tender' => pb2bTenderResource::make($tender)->forSupplier()->resolve(),
            'application' => $this->serializeApplication($application, true),
            'gates' => $this->gatePayload($application, $tender),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeApplication(
        pb2bTenderApplication $application,
        bool $include_prices,
        bool $detail = true,
        bool $include_winner = false,
        ?array $summary = null
    ): array {
        $resource = pb2bTenderApplicationResource::make($application)
            ->withPrices($include_prices)
            ->withWinnerFlags($include_winner);
        if ($detail) {
            $items = $application->getItems();
            $documents = $application->getDocuments();
            $resource->withItems($items)->withDocuments($documents)->withCriteria($application->getCriteria());
            $items_count = count($items);
            $documents_count = count(array_filter($documents, static fn(array $row): bool => (int) ($row['file_link_id'] ?? 0) > 0));
        } else {
            $items_count = (int) ($summary['items_count'] ?? 0);
            $documents_count = (int) ($summary['documents_count'] ?? 0);
        }
        $payload = $resource->resolve();
        $payload['has_documents'] = $documents_count > 0 ? 1 : 0;
        $payload['documents_count'] = $documents_count;
        $payload['items_count'] = $items_count;
        $payload['supplier'] = $this->serializeSupplierCompany((int) $payload['supplier_company_id'], $summary);
        if (!$detail) {
            unset($payload['items'], $payload['documents'], $payload['criteria']);
        }

        return $payload;
    }

    /**
     * @return array{id: int, name: string, fullname: string, inn: string, address: string, phone: string}|null
     */
    private function serializeSupplierCompany(int $company_id, ?array $summary = null): ?array
    {
        if ($summary !== null) {
            $row = $summary['supplier'];
            if (!$row) {
                return null;
            }
            $company = new pb2bCompany();
            foreach ($row as $field => $value) {
                $company->setField($field, $value);
            }
        } else {
            $company = new pb2bCompany($company_id);
            if (!$company->id) {
                return null;
            }
            $row = $company->data;
        }

        return array(
            'id' => (int) $row['id'],
            'name' => (string) ($row['name'] ?? ''),
            'fullname' => trim($company->getFullName()),
            'inn' => (string) ($row['inn'] ?? ''),
            'address' => trim((string) ($row['jur_address'] ?? '')),
            'phone' => trim((string) ($row['phone'] ?? '')),
        );
    }

    /**
     * Карточка для buyer: имена позиций/критериев с извещения (цены по-прежнему маскирует Resource).
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function enrichBuyerApplicationDetail(
        array $payload,
        pb2bTender $tender,
        pb2bTenderApplication $application
    ): array {
        $item_map = array_column($tender->getItems(), null, 'id');
        $items = array();
        foreach ((array) ($payload['items'] ?? array()) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $tid = (int) ($row['tender_item_id'] ?? 0);
            $src = $item_map[$tid] ?? array();
            $row['name'] = (string) ($src['name'] ?? '');
            $row['delivery_place'] = $src['delivery_place'] ?? null;
            $row['unit'] = $row['unit'] ?? ($src['unit'] ?? null);
            $row['qty'] = $row['qty'] ?? ($src['qty'] ?? null);
            if (empty($row['vat_rate']) && isset($src['vat_rate'])) {
                $row['vat_rate'] = $src['vat_rate'];
            }
            if (!array_key_exists('max_price_no_vat', $row)) {
                $hide_price = !empty($tender->data['hide_initial_price']);
                $row['max_price_no_vat'] = $hide_price ? null : ($src['max_price_no_vat'] ?? null);
            }
            $items[] = $row;
        }
        $payload['items'] = $items;

        $criterion_map = array_column($tender->getCriteria(), null, 'id');
        $answers = array_column($payload['criteria'], null, 'criterion_id');
        $criteria = array();
        foreach ($criterion_map as $cid => $src) {
            $row = $answers[$cid] ?? array(
                'criterion_id' => $cid,
                'value' => null,
                'file_link_id' => null,
                'confirmed' => 0,
            );
            $row['name'] = (string) ($src['name'] ?? $src['title'] ?? '');
            $row['is_mandatory'] = !empty($src['is_mandatory']) ? 1 : 0;
            $row['type'] = $this->criterionAnswerType($src);
            $row['confirmation_text'] = (string) ($src['confirmation_text'] ?? '');
            $row['file_name'] = (string) ($row['file_name'] ?? '');
            $criteria[] = $row;
        }
        $payload['criteria'] = $criteria;
        $payload['gate_actions'] = $this->buyerGateActions($application, $tender);

        return $payload;
    }

    /**
     * @return array{
     *   proposal: bool,
     *   nonprice_present: bool,
     *   nonprice_done: bool,
     *   approval_ok: bool,
     *   qualification_ok: bool,
     *   approval_status: string,
     *   qualification_status: string
     * }
     */
    private function gatePayload(?pb2bTenderApplication $application, pb2bTender $tender): array
    {
        $row = $application ? $application->data : array();
        $tender_row = $tender->data;
        $criteria = $tender->getCriteria();
        $has_mandatory = false;
        foreach ($criteria as $criterion) {
            if (!empty($criterion['is_mandatory'])) {
                $has_mandatory = true;
                break;
            }
        }
        $approval_required = !empty($tender_row['approval_required']);
        $approval_code = (string) ($row['approval_status'] ?? ($approval_required ? 'pending' : 'not_required'));
        $qualification_code = (string) ($row['qualification_status'] ?? 'not_required');
        $nonprice_done = $application && ($has_mandatory
            ? $this->mandatoryCriteriaAnswered($application, $criteria)
            : !empty($row['nonprice_done']));
        $nonprice_ok = !$has_mandatory || $nonprice_done;
        $proposal = $application
            ? $nonprice_ok
                && (in_array((string) ($row['approval_status'] ?? ''), array('approved', 'not_required'), true) || !$approval_required)
                && in_array((string) ($row['qualification_status'] ?? ''), array('passed', 'not_required'), true)
            : !$has_mandatory && !$approval_required && empty($tender_row['past_prequal_tender_id']);
        if ($approval_code === '' && !$approval_required) {
            $approval_code = 'not_required';
        }
        if ($qualification_code === '') {
            $qualification_code = 'not_required';
        }

        return array(
            'proposal' => $proposal,
            'nonprice_present' => (bool) $criteria,
            'nonprice_done' => !$criteria || $nonprice_done,
            'nonprice_required' => $has_mandatory,
            'approval_ok' => in_array($approval_code, array('approved', 'not_required'), true) || !$approval_required,
            'approval_required' => $approval_required,
            'qualification_ok' => in_array($qualification_code, array('passed', 'not_required'), true),
            'qualification_required' => $qualification_code !== 'not_required',
            'approval_status' => $approval_code,
            'qualification_status' => $qualification_code,
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serializeNoticeItems(pb2bTender $tender): array
    {
        $include_price = empty($tender->data['hide_initial_price']);

        return array_map(
            static fn(array $item): array => pb2bTenderItemResource::make($item)
                ->withInitialPrice($include_price)->resolve(),
            $tender->getItems()
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serializeNoticeCriteria(pb2bTender $tender): array
    {
        $out = array();
        foreach ($tender->getCriteria() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $out[] = array(
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'type' => $this->criterionAnswerType($row),
                'is_mandatory' => !empty($row['is_mandatory']) ? 1 : 0,
                'description' => (string) ($row['description'] ?? ''),
                'confirmation_text' => (string) ($row['confirmation_text'] ?? ''),
                'sort' => (int) ($row['sort'] ?? 0),
            );
        }

        return $out;
    }

    private function mandatoryCriteriaAnswered(pb2bTenderApplication $application, array $criteria): bool
    {
        $answers = array_column($application->getCriteria(), null, 'criterion_id');
        $file_ids = array_values(array_filter(array_column($answers, 'file_link_id')));
        $links = $file_ids ? (new pb2bFileLinkCollection())->getByIds($file_ids) : array();
        foreach ($criteria as $criterion) {
            if (empty($criterion['is_mandatory'])) {
                continue;
            }
            $answer = $answers[(int) $criterion['id']] ?? null;
            if (!$answer) {
                return false;
            }
            $type = $this->criterionAnswerType($criterion);
            $value = trim((string) ($answer['value'] ?? ''));
            if ($type === 'text' && $value === '') {
                return false;
            }
            if ($type === 'date' && !$this->isCriterionDate($value)) {
                return false;
            }
            if ($type === 'confirmation' && empty($answer['confirmed'])) {
                return false;
            }
            if ($type === 'file') {
                $link = $links[(int) ($answer['file_link_id'] ?? 0)] ?? null;
                if (!$link || empty($link['stored_file_id'])
                    || (int) $link['owner_company_id'] !== (int) $application->data['supplier_company_id']) {
                    return false;
                }
                $extra = json_decode((string) ($link['extra'] ?? ''), true);
                if (($extra['purpose'] ?? '') === 'tender_application_upload'
                    && (int) ($extra['tender_id'] ?? 0) !== (int) $application->data['tender_id']) {
                    return false;
                }
                if (!pb2bStorage::disk($link['storage_disk'])->exists($link['storage_path'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function saveItems(pb2bTenderApplication $application, pb2bTender $tender, array $rows): void
    {
        $notice = array_column($tender->getItems(), null, 'id');
        $remaining = array_column($application->getItems(), null, 'tender_item_id');
        $seen = array();
        foreach ($rows as $sort => $row) {
            if (!is_array($row)) {
                throw new waException('Некорректная позиция заявки', pb2bHttpStatus::BAD_REQUEST);
            }
            $tender_item_id = (int) ($row['tender_item_id'] ?? $row['id'] ?? 0);
            if (!isset($notice[$tender_item_id]) || isset($seen[$tender_item_id])) {
                throw new waException('Неизвестная или повторная позиция извещения', pb2bHttpStatus::BAD_REQUEST);
            }
            $seen[$tender_item_id] = true;
            $item = new pb2bTenderApplicationItem(isset($remaining[$tender_item_id]) ? (int) $remaining[$tender_item_id]['id'] : null);
            $save = $item->save(array(
                'application_id' => (int) $application->id,
                'tender_item_id' => $tender_item_id,
                'qty' => $row['qty'] ?? $notice[$tender_item_id]['qty'] ?? null,
                'unit' => $row['unit'] ?? $notice[$tender_item_id]['unit'] ?? null,
                'price_per_unit' => $row['price_per_unit'] ?? null,
                'vat_rate' => $row['vat_rate'] ?? $notice[$tender_item_id]['vat_rate'] ?? null,
                'sort' => (int) ($row['sort'] ?? $sort),
            ));
            if (!empty($save['error'])) {
                throw new waException((string) ($save['message'] ?? 'Не удалось сохранить цену'), pb2bHttpStatus::BAD_REQUEST);
            }
            unset($remaining[$tender_item_id]);
        }
        foreach ($remaining as $row) {
            $result = (new pb2bTenderApplicationItem((int) $row['id']))->delete();
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось удалить цену'), pb2bHttpStatus::BAD_REQUEST);
            }
        }
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function saveCriteria(pb2bTenderApplication $application, pb2bTender $tender, array $rows): void
    {
        foreach ($rows as $row) {
            if (!is_array($row)) {
                throw new waException('Некорректные данные заявки', pb2bHttpStatus::BAD_REQUEST);
            }
        }
        $allowed = array_column($tender->getCriteria(), null, 'id');
        $remaining = array_column($application->getCriteria(), null, 'criterion_id');
        $this->assertApplicationFileLinks($application, array_column($rows, 'file_link_id'));
        $seen = array();
        foreach ($rows as $row) {
            $criterion_id = (int) ($row['criterion_id'] ?? $row['id'] ?? 0);
            if (!isset($allowed[$criterion_id]) || isset($seen[$criterion_id])) {
                throw new waException('Неизвестный или повторный критерий', pb2bHttpStatus::BAD_REQUEST);
            }
            $seen[$criterion_id] = true;
            $type = $this->criterionAnswerType($allowed[$criterion_id]);
            $value = trim((string) ($row['value'] ?? ''));
            if ($type === 'date' && $value !== '' && !$this->isCriterionDate($value)) {
                throw new waException('Укажите дату в формате ГГГГ-ММ-ДД', pb2bHttpStatus::BAD_REQUEST);
            }
            $object = new pb2bTenderApplicationCriterion(isset($remaining[$criterion_id]) ? (int) $remaining[$criterion_id]['id'] : null);
            $save = $object->save(array(
                'application_id' => (int) $application->id,
                'criterion_id' => $criterion_id,
                'value' => in_array($type, array('text', 'date'), true) ? $value : null,
                'file_link_id' => $type === 'file' ? ($row['file_link_id'] ?? null) : null,
                'confirmed' => $type === 'confirmation' && !empty($row['confirmed']) ? 1 : 0,
            ));
            if (!empty($save['error'])) {
                throw new waException((string) ($save['message'] ?? 'Не удалось сохранить ответ на критерий'), pb2bHttpStatus::BAD_REQUEST);
            }
            unset($remaining[$criterion_id]);
        }
        foreach ($remaining as $row) {
            $result = (new pb2bTenderApplicationCriterion((int) $row['id']))->delete();
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось удалить ответ на критерий'), pb2bHttpStatus::BAD_REQUEST);
            }
        }
    }

    private function criterionAnswerType(array $criterion): string
    {
        $type = (string) ($criterion['type'] ?? 'text');
        return $type === 'non_price' || $type === '' ? 'text' : $type;
    }

    private function isCriterionDate(string $value): bool
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return false;
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        return $date !== false && $date->format('Y-m-d') === $value;
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function saveDocuments(pb2bTenderApplication $application, pb2bTender $tender, array $rows): void
    {
        foreach ($rows as $row) {
            if (!is_array($row)) {
                throw new waException('Некорректные данные заявки', pb2bHttpStatus::BAD_REQUEST);
            }
        }
        $requirements = array_column($tender->getDocuments(pb2bTenderDocument::KIND_REQUIREMENT), null, 'id');
        $remaining = array_column($application->getDocuments(), null, 'id');
        $this->assertApplicationFileLinks($application, array_column($rows, 'file_link_id'));
        $seen = array();
        $seen_requirements = array();
        foreach ($rows as $sort => $row) {
            $id = (int) ($row['id'] ?? 0);
            if ($id > 0 && (!isset($remaining[$id]) || isset($seen[$id]))) {
                throw new waException('Документ заявки не найден или указан повторно', pb2bHttpStatus::BAD_REQUEST);
            }
            $seen[$id] = true;
            $requirement_id = (int) ($row['tender_document_id'] ?? 0);
            if ($requirement_id > 0 && (!isset($requirements[$requirement_id]) || isset($seen_requirements[$requirement_id]))) {
                throw new waException('Неизвестное или повторное требование к документу', pb2bHttpStatus::BAD_REQUEST);
            }
            if ($requirement_id > 0) {
                $seen_requirements[$requirement_id] = true;
            }
            $object = new pb2bTenderApplicationDocument($id > 0 ? $id : null);
            $save = $object->save(array(
                'application_id' => (int) $application->id,
                'tender_document_id' => $requirement_id > 0 ? $requirement_id : null,
                'name' => $row['name'] ?? '',
                'comment' => $row['comment'] ?? null,
                'file_link_id' => $row['file_link_id'] ?? null,
                'sort' => (int) ($row['sort'] ?? $sort),
            ));
            if (!empty($save['error'])) {
                throw new waException((string) ($save['message'] ?? 'Не удалось сохранить документ заявки'), pb2bHttpStatus::BAD_REQUEST);
            }
            unset($remaining[$id]);
        }
        foreach ($remaining as $row) {
            $result = (new pb2bTenderApplicationDocument((int) $row['id']))->delete();
            if (!empty($result['error'])) {
                throw new waException((string) ($result['message'] ?? 'Не удалось удалить документ заявки'), pb2bHttpStatus::BAD_REQUEST);
            }
        }
    }

    private function assertApplicationFileLinks(pb2bTenderApplication $application, array $file_link_ids): void
    {
        foreach ($file_link_ids as $id) {
            if ($id !== null && $id !== '' && (!is_int($id) && !is_string($id) || !ctype_digit((string) $id))) {
                throw new waException('Некорректный идентификатор вложения', pb2bHttpStatus::BAD_REQUEST);
            }
        }
        $ids = array_values(array_unique(array_filter(array_map('intval', $file_link_ids))));
        if (!$ids) {
            return;
        }
        $links = (new pb2bFileLinkCollection())->getByIds($ids);
        $attached = null;
        foreach ($ids as $id) {
            $link = $links[$id] ?? null;
            if (!$link || empty($link['stored_file_id'])) {
                throw new waException('Вложение не найдено. Загрузите файл повторно', pb2bHttpStatus::NOT_FOUND);
            }
            if ((int) $link['owner_company_id'] !== (int) $application->data['supplier_company_id']) {
                throw new waException('Файл не принадлежит поставщику', pb2bHttpStatus::FORBIDDEN);
            }
            $extra = json_decode((string) ($link['extra'] ?? ''), true);
            if (($extra['purpose'] ?? '') === 'tender_application_upload') {
                if ((int) ($extra['tender_id'] ?? 0) !== (int) $application->data['tender_id']) {
                    throw new waException('Файл загружен для другого тендера', pb2bHttpStatus::FORBIDDEN);
                }
            } else {
                if ($attached === null) {
                    $attached = array_fill_keys(array_merge(
                        array_column($application->getDocuments(), 'file_link_id'),
                        array_column($application->getCriteria(), 'file_link_id')
                    ), true);
                }
                if (!isset($attached[$id])) {
                    throw new waException('Файл не связан с заявкой. Загрузите его повторно', pb2bHttpStatus::FORBIDDEN);
                }
            }
            if (!pb2bStorage::disk($link['storage_disk'])->exists($link['storage_path'])) {
                throw new waException('Файл отсутствует в хранилище. Загрузите его повторно', pb2bHttpStatus::NOT_FOUND);
            }
        }
    }

    private function assertPricesComplete(pb2bTenderApplication $application, pb2bTender $tender): void
    {
        $prices = array();
        $rows = $application->getItems();
        foreach (is_array($rows) ? $rows : array() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $prices[(int) ($row['tender_item_id'] ?? 0)] = (float) ($row['price_per_unit'] ?? 0);
        }
        foreach ($tender->getItems() as $item) {
            if (!is_array($item)) {
                continue;
            }
            $item_id = (int) ($item['id'] ?? 0);
            if ($item_id <= 0) {
                continue;
            }
            if (($prices[$item_id] ?? 0) <= 0) {
                throw new waException('Укажите цену по каждой позиции извещения', pb2bHttpStatus::CONFLICT);
            }
        }
    }

    private function assertRequiredDocuments(pb2bTenderApplication $application, pb2bTender $tender): void
    {
        $uploaded = array();
        $requirements = array_column($tender->getDocuments(pb2bTenderDocument::KIND_REQUIREMENT), null, 'id');
        $rows = $application->getDocuments();
        $this->assertApplicationFileLinks($application, array_column($rows, 'file_link_id'));
        $this->assertApplicationFileLinks($application, array_column($application->getCriteria(), 'file_link_id'));
        foreach (is_array($rows) ? $rows : array() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $requirement_id = (int) ($row['tender_document_id'] ?? 0);
            if ($requirement_id > 0 && !isset($requirements[$requirement_id])) {
                throw new waException('Неизвестное требование к документу', pb2bHttpStatus::CONFLICT);
            }
            if ($requirement_id > 0 && (int) ($row['file_link_id'] ?? 0) > 0) {
                $uploaded[$requirement_id] = true;
            }
        }
        foreach ($requirements as $doc) {
            if (!is_array($doc) || empty($doc['is_required'])) {
                continue;
            }
            $doc_id = (int) ($doc['id'] ?? 0);
            if ($doc_id > 0 && empty($uploaded[$doc_id])) {
                throw new waException('Загрузите обязательные документы', pb2bHttpStatus::CONFLICT);
            }
        }
    }

    /**
     * @return list<pb2bTender>
     */
    private function candidateTendersForSupplier(int $supplier_company_id): array
    {
        $accepting_applications_id = pb2bTenderStatus::ACCEPTING_APPLICATIONS->id();
        $published_status_id = pb2bTenderStatus::PUBLISHED->id();

        $model = new pb2bTenderModel();
        // Актуальные по времени сверху: дедлайн не прошёл → приём/опубликован → ближайший end_at.
        $rows = $model->query(
            'SELECT id FROM pb2b_tender
             WHERE IFNULL(is_deleted, 0) = 0
               AND organizer_company_id <> i:supplier_id
             ORDER BY
               CASE
                 WHEN end_at IS NULL OR end_at = \'0000-00-00 00:00:00\' OR end_at > NOW() THEN 0
                 ELSE 1
               END ASC,
               CASE
                 WHEN status = i:accepting_applications_id THEN 0
                 WHEN status = i:published_status_id THEN 1
                 ELSE 2
               END ASC,
               CASE
                 WHEN end_at IS NULL OR end_at = \'0000-00-00 00:00:00\' THEN 1
                 ELSE 0
               END ASC,
               end_at ASC,
               id DESC',
            array(
                'supplier_id' => $supplier_company_id,
                'accepting_applications_id' => $accepting_applications_id,
                'published_status_id' => $published_status_id,
            )
        )->fetchAll();
        $out = array();
        foreach (is_array($rows) ? $rows : array() as $row) {
            $tender = new pb2bTender((int) ($row['id'] ?? 0));
            if ((int) $tender->id) {
                $this->statusService->ensureReceptionOpen($tender);
                $out[] = $tender;
            }
        }

        return $out;
    }

    private function openDueReception(): void
    {
        $published_id = pb2bTenderStatus::PUBLISHED->id();
        if ($published_id <= 0) {
            return;
        }
        $rows = (new pb2bTenderModel())->query(
            'SELECT id FROM pb2b_tender WHERE status = ? AND IFNULL(is_deleted, 0) = 0',
            $published_id
        )->fetchAll();
        foreach (is_array($rows) ? $rows : array() as $row) {
            $tender = new pb2bTender((int) ($row['id'] ?? 0));
            if ((int) $tender->id) {
                $this->statusService->ensureReceptionOpen($tender);
            }
        }
    }

    private function comparisonTotals(array $items): array
    {
        $with_vat = 0.0;
        $without_vat = 0.0;
        $rates = array();
        $known = (bool) $items;
        foreach ($items as $item) {
            $amount = (float) $item['line_total'];
            $with_vat += $amount;
            $raw = trim((string) ($item['vat_rate'] ?? ''));
            if (in_array($raw, array('none', 'without', 'Без НДС'), true)) {
                $rate = 0.0;
                $label = 'Без НДС';
            } elseif (preg_match('/^(\d+(?:[.,]\d+)?)\s*%?$/u', $raw, $matches)) {
                $rate = (float) str_replace(',', '.', $matches[1]);
                $label = (string) $rate . '%';
            } else {
                $known = false;
                continue;
            }
            $rates[$label] = $label;
            $without_vat += $amount / (1 + $rate / 100);
        }

        return array(
            'with_vat' => round($with_vat, 2),
            'without_vat' => $known ? round($without_vat, 2) : null,
            'vat' => $known ? round($with_vat - $without_vat, 2) : null,
            'vat_rates' => $known ? array_values($rates) : array(),
        );
    }

    /**
     * @param array<string, mixed> $row
     * @param array<int, array<string, mixed>> $notice_items
     * @return array<string, mixed>|null
     */
    private function buildRankingCandidate(
        pb2bTenderApplication $application,
        array $row,
        array $notice_items,
        string $admission
    ): ?array {
        $application_id = (int) $application->id;
        $item_rows = $application->getItems();
        $by_tid = array();
        foreach (is_array($item_rows) ? $item_rows : array() as $item_row) {
            if (!is_array($item_row)) {
                continue;
            }
            $tid = (int) ($item_row['tender_item_id'] ?? 0);
            if ($tid > 0) {
                $by_tid[$tid] = $item_row;
            }
        }

        $total = 0.0;
        $items = array();
        foreach ($notice_items as $tid => $meta) {
            if (!isset($by_tid[$tid])) {
                return null;
            }
            $src = $by_tid[$tid];
            if (!array_key_exists('price_per_unit', $src) || $src['price_per_unit'] === null || $src['price_per_unit'] === '') {
                return null;
            }
            $price = round((float) $src['price_per_unit'], 2);
            if ($price <= 0) {
                return null;
            }
            $qty_raw = $src['qty'] ?? ($meta['qty'] ?? null);
            if ($qty_raw === null || $qty_raw === '') {
                return null;
            }
            $qty = (float) $qty_raw;
            $line_total = round($qty * $price, 2);
            $total += $line_total;
            $unit = $src['unit'] ?? ($meta['unit'] ?? null);
            $items[] = array(
                'tender_item_id' => $tid,
                'name' => (string) ($meta['name'] ?? ''),
                'qty' => $qty_raw,
                'unit' => $unit,
                'price_per_unit' => $price,
                'vat_rate' => $src['vat_rate'] ?? ($meta['vat_rate'] ?? null),
                'line_total' => $line_total,
                'sort' => (int) ($meta['sort'] ?? 0),
            );
        }

        $submitted_at = trim((string) ($row['submitted_at'] ?? ''));
        $supplier = $this->serializeSupplierCompany((int) ($row['supplier_company_id'] ?? 0));

        return array(
            'application_id' => $application_id,
            'supplier' => $supplier,
            'submitted_at' => $submitted_at !== '' ? $submitted_at : null,
            'admission_status' => $admission,
            'total' => round($total, 2),
            'rank' => 0,
            'items' => $items,
        );
    }

    /**
     * @param list<array<string, mixed>> $candidates
     */
    private function compareRankingByTotal(array $a, array $b): int
    {
        $cmp = $this->compareFloat((float) ($a['total'] ?? 0), (float) ($b['total'] ?? 0));
        if ($cmp !== 0) {
            return $cmp;
        }

        return $this->compareRankingTieBreak($a, $b);
    }

    /**
     * @param list<array<string, mixed>> $candidates
     */
    private function compareRankingByRank(array $a, array $b): int
    {
        $ra = (int) ($a['rank'] ?? 0);
        $rb = (int) ($b['rank'] ?? 0);
        if ($ra !== $rb) {
            return $ra <=> $rb;
        }

        return $this->compareRankingTieBreak($a, $b);
    }

    /**
     * @param array<string, mixed> $a
     * @param array<string, mixed> $b
     */
    private function compareRankingTieBreak(array $a, array $b): int
    {
        $sa = (string) ($a['submitted_at'] ?? '');
        $sb = (string) ($b['submitted_at'] ?? '');
        if ($sa !== $sb) {
            if ($sa === '') {
                return 1;
            }
            if ($sb === '') {
                return -1;
            }

            return $sa <=> $sb;
        }

        return ((int) ($a['application_id'] ?? 0)) <=> ((int) ($b['application_id'] ?? 0));
    }

    private function compareFloat(float $a, float $b): int
    {
        $eps = 0.00001;
        if (abs($a - $b) < $eps) {
            return 0;
        }

        return $a < $b ? -1 : 1;
    }

    /**
     * Competition-ранг: 1,2,2,4 при одинаковом значении.
     * Список уже отсортирован по возрастанию value_key.
     *
     * @param list<array<string, mixed>> $rows
     */
    private function assignCompetitionRanks(array &$rows, string $value_key, string $rank_key): void
    {
        $prev = null;
        $rank = 0;
        $index = 0;
        foreach ($rows as &$row) {
            $index += 1;
            $value = (float) ($row[$value_key] ?? 0);
            if ($prev === null || $this->compareFloat($value, $prev) !== 0) {
                $rank = $index;
                $prev = $value;
            }
            $row[$rank_key] = $rank;
        }
        unset($row);
    }

    /**
     * @param list<array<string, mixed>> $candidates
     * @param array<int, array<string, mixed>> $notice_items
     */
    private function assignItemizedRanks(array &$candidates, array $notice_items): void
    {
        foreach ($notice_items as $tid => $_meta) {
            $entries = array();
            foreach ($candidates as $idx => $candidate) {
                foreach ((array) ($candidate['items'] ?? array()) as $item_idx => $item) {
                    if ((int) ($item['tender_item_id'] ?? 0) !== (int) $tid) {
                        continue;
                    }
                    $entries[] = array(
                        'candidate_idx' => $idx,
                        'item_idx' => $item_idx,
                        'price_per_unit' => (float) ($item['price_per_unit'] ?? 0),
                        'application_id' => (int) ($candidate['application_id'] ?? 0),
                        'submitted_at' => (string) ($candidate['submitted_at'] ?? ''),
                    );
                }
            }
            usort($entries, function (array $a, array $b) {
                $cmp = $this->compareFloat(
                    (float) $a['price_per_unit'],
                    (float) $b['price_per_unit']
                );
                if ($cmp !== 0) {
                    return $cmp;
                }
                return $this->compareRankingTieBreak($a, $b);
            });
            $this->assignCompetitionRanks($entries, 'price_per_unit', 'rank');
            foreach ($entries as $entry) {
                $ci = (int) $entry['candidate_idx'];
                $ii = (int) $entry['item_idx'];
                if (!isset($candidates[$ci]['items'][$ii])) {
                    continue;
                }
                $candidates[$ci]['items'][$ii]['rank'] = (int) $entry['rank'];
            }
        }

        foreach ($candidates as &$candidate) {
            $items = (array) ($candidate['items'] ?? array());
            usort($items, function (array $a, array $b) {
                $sa = (int) ($a['sort'] ?? 0);
                $sb = (int) ($b['sort'] ?? 0);
                if ($sa !== $sb) {
                    return $sa <=> $sb;
                }

                return ((int) ($a['tender_item_id'] ?? 0)) <=> ((int) ($b['tender_item_id'] ?? 0));
            });
            foreach ($items as &$item) {
                unset($item['sort']);
            }
            unset($item);
            $candidate['items'] = array_values($items);
        }
        unset($candidate);
    }

    /**
     * Заявки, участвующие в ranking и выборе победителя.
     * submitted + admission != rejected + viewAsBuyer.
     *
     * @return list<array{
     *   application: pb2bTenderApplication,
     *   row: array<string, mixed>,
     *   admission_status: string
     * }>
     */
    private function eligibleApplicationsForBuyer(pb2bTender $tender, pb2bCompany $company): array
    {
        $tender_id = (int) $tender->id;
        $out = array();
        foreach ($this->applicationsForTender($tender_id) as $application) {
            if (!$this->isEligibleForWinnerSelection($application, $company, $tender_id)) {
                continue;
            }
            $row = $application->data;
            $admission = trim((string) ($row['admission_status'] ?? 'pending'));
            if ($admission === '') {
                $admission = 'pending';
            }
            $out[] = array(
                'application' => $application,
                'row' => $row,
                'admission_status' => $admission,
            );
        }

        return $out;
    }

    /**
     * Одна заявка в пуле победителя / ranking.
     */
    private function isEligibleForWinnerSelection(
        pb2bTenderApplication $application,
        pb2bCompany $company,
        int $tender_id
    ): bool {
        if (!(int) $application->id || $tender_id <= 0) {
            return false;
        }
        $row = $application->data;
        if ((int) ($row['tender_id'] ?? 0) !== $tender_id) {
            return false;
        }
        if ($application->getStatusCode() !== pb2bTenderApplication::STATUS_SUBMITTED) {
            return false;
        }
        if (!pb2bTenderApplicationPolicy::viewAsBuyer($application, $company)) {
            return false;
        }
        $admission = trim((string) ($row['admission_status'] ?? 'pending'));
        if ($admission === '') {
            $admission = 'pending';
        }

        return $admission !== 'rejected';
    }

    /**
     * @throws waException
     */
    private function assertWinnerPriceRequest(pb2bTender $tender): void
    {
        if (pb2bTenderType::tryFromId((int) $tender->data['type']) !== pb2bTenderType::PRICE_REQUEST) {
            throw new waException(
                'Выбор победителя доступен только для запроса цен',
                pb2bHttpStatus::CONFLICT
            );
        }
    }

    /**
     * @throws waException
     */
    private function assertWinnerReviewStarted(pb2bTender $tender): void
    {
        $status_code = (pb2bTenderStatus::tryFromId((int) $tender->data['status'])?->value ?? '');
        if (!pb2bTenderStatus::isBuyerPriceVisible($status_code)) {
            throw new waException(
                'Сначала начните рассмотрение заявок',
                pb2bHttpStatus::CONFLICT
            );
        }
    }

    /**
     * @throws waException
     */
    private function assertWinnerDraftEditable(pb2bTender $tender): void
    {
        $this->assertWinnerReviewStarted($tender);
        $row = $tender->data;
        if (!empty($row['winner_selection_completed'])) {
            throw new waException(
                'Выбор победителя уже завершён',
                pb2bHttpStatus::CONFLICT
            );
        }
        if (!$tender->canSelectWinner()) {
            throw new waException(
                'Выбор победителя недоступен из текущего статуса',
                pb2bHttpStatus::CONFLICT
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildWinnerState(pb2bTender $tender, pb2bCompany $company): array
    {
        $row = $tender->data;
        $status_code = (pb2bTenderStatus::tryFromId((int) $tender->data['status'])?->value ?? '');
        $mode = $tender->getWinnerSelectionModeCode();
        $completed = !empty($row['winner_selection_completed']) ? 1 : 0;
        $finished_without = !empty($row['finished_without_winner']) ? 1 : 0;
        $reason = trim((string) ($row['finished_without_winner_reason'] ?? ''));
        $itemized = !empty($row['itemized_enabled']) ? 1 : 0;

        $can_edit = (int) (pb2bTenderPolicy::selectWinner($tender, $company) && $tender->canSelectWinner());

        $notice_items = $tender->getItems();
        $eligible_applications = $this->eligibleApplicationsForBuyer($tender, $company);
        $awards = $this->collectWinnerAwards($eligible_applications, $notice_items, $mode);
        $can_complete = 0;
        if ($can_edit && $finished_without === 0) {
            $can_complete = $this->isWinnerDraftComplete($notice_items, $mode, $awards) ? 1 : 0;
        }

        $eligible = array();
        foreach ($eligible_applications as $entry) {
            $app_row = $entry['row'];
            $eligible[] = array(
                'application_id' => (int) $entry['application']->id,
                'supplier' => $this->serializeSupplierCompany((int) ($app_row['supplier_company_id'] ?? 0)),
                'admission_status' => $entry['admission_status'],
                'is_winner' => !empty($app_row['is_winner']) ? 1 : 0,
            );
        }

        return array(
            'tender_id' => (int) $tender->id,
            'status_code' => $status_code,
            'itemized_enabled' => $itemized,
            'winner_selection_mode' => $mode,
            'winner_selection_completed' => $completed,
            'finished_without_winner' => $finished_without,
            'finished_without_winner_reason' => $reason !== '' ? $reason : null,
            'can_edit' => $can_edit,
            'can_complete' => $can_complete,
            'awards' => $awards,
            'eligible' => $eligible,
        );
    }

    /**
     * @return array{application_id: ?int, items: list<array<string, mixed>>}
     */
    private function collectWinnerAwards(array $eligible_applications, array $notice_items, string $mode): array
    {
        $application_id = null;
        $items = array();
        $notice_names = array();
        foreach ($notice_items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $tid = (int) ($item['id'] ?? 0);
            if ($tid > 0) {
                $notice_names[$tid] = (string) ($item['name'] ?? '');
            }
        }

        foreach ($eligible_applications as $entry) {
            $app = $entry['application'];
            $app_row = $entry['row'];
            $aid = (int) $app->id;
            if ($mode === 'all' && !empty($app_row['is_winner'])) {
                $application_id = $aid;
            }
            if ($mode !== 'positional') {
                continue;
            }
            foreach ($app->getItems() as $item_row) {
                if (!is_array($item_row) || empty($item_row['is_winner'])) {
                    continue;
                }
                $tid = (int) ($item_row['tender_item_id'] ?? 0);
                if ($tid <= 0) {
                    continue;
                }
                $items[] = array(
                    'tender_item_id' => $tid,
                    'application_id' => $aid,
                    'name' => (string) ($notice_names[$tid] ?? ''),
                );
            }
        }

        return array(
            'application_id' => $application_id,
            'items' => $items,
        );
    }

    /**
     * @param array{application_id: ?int, items: list<array<string, mixed>>} $awards
     */
    private function isWinnerDraftComplete(
        array $notice_items,
        string $mode,
        array $awards
    ): bool {
        if ($mode === 'all') {
            return !empty($awards['application_id']);
        }
        if ($mode !== 'positional') {
            return false;
        }
        $notice_ids = array();
        foreach ($notice_items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $tid = (int) ($item['id'] ?? 0);
            if ($tid > 0) {
                $notice_ids[$tid] = true;
            }
        }
        if ($notice_ids === array()) {
            return false;
        }
        $covered = array();
        foreach ((array) ($awards['items'] ?? array()) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $tid = (int) ($row['tender_item_id'] ?? 0);
            $aid = (int) ($row['application_id'] ?? 0);
            if ($tid <= 0 || $aid <= 0 || !isset($notice_ids[$tid])) {
                continue;
            }
            if (isset($covered[$tid])) {
                return false;
            }
            $covered[$tid] = $aid;
        }

        return count($covered) === count($notice_ids);
    }

    private function clearAllWinnerFlags(pb2bTender $tender): void
    {
        $application_ids = array();
        foreach ($this->applicationsForTender((int) $tender->id) as $application) {
            $application_ids[] = (int) $application->id;
            $row = $application->data;
            if (!empty($row['is_winner'])) {
                unset($row['create_datetime'], $row['update_datetime']);
                $row['is_winner'] = 0;
                $row['_winner_via_service'] = 1;
                $saved = $application->save($row);
                if (!empty($saved['error'])) {
                    throw new waException(
                        (string) ($saved['message'] ?? 'Не удалось сбросить флаг победителя'),
                        pb2bHttpStatus::BAD_REQUEST
                    );
                }
            }
        }
        if (!$application_ids) {
            return;
        }
        $rows = (new pb2bTenderApplicationItemCollection(array('col' => array(
            'application_id' => array('simile' => 'IN', 'value' => $application_ids),
            'is_winner' => array('value' => 1),
        ))))->getCollection();
        foreach ($rows as $item_row) {
            $item_id = (int) $item_row['id'];
            $object = new pb2bTenderApplicationItem($item_id);
            $save_row = $item_row;
            unset($save_row['create_datetime'], $save_row['update_datetime']);
            $save_row['is_winner'] = 0;
            $save_row['_winner_via_service'] = 1;
            $saved = $object->save($save_row);
            if (!empty($saved['error'])) {
                throw new waException(
                    (string) ($saved['message'] ?? 'Не удалось сбросить флаг победителя позиции'),
                    pb2bHttpStatus::BAD_REQUEST
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $fields
     * @throws waException
     */
    private function saveTenderWinnerDraftFields(pb2bTender $tender, array $fields): void
    {
        $data = $tender->data;
        unset($data['create_datetime'], $data['update_datetime']);
        foreach ($fields as $key => $value) {
            $data[$key] = $value;
        }
        $data['_winner_via_service'] = 1;
        $saved = $tender->save($data);
        if (!empty($saved['error'])) {
            throw new waException(
                (string) ($saved['message'] ?? 'Не удалось сохранить параметры выбора победителя'),
                pb2bHttpStatus::BAD_REQUEST
            );
        }
    }

    /**
     * @return list<pb2bTenderApplication>
     */
    private function applicationsForTender(int $tender_id): array
    {
        $rows = (new pb2bTenderApplicationCollection('tender_id=' . $tender_id))->getCollection(array(
            'select' => array('id' => null),
        ));
        $out = array();
        foreach ($rows as $row) {
            $out[] = new pb2bTenderApplication((int) $row['id']);
        }

        return $out;
    }
}
