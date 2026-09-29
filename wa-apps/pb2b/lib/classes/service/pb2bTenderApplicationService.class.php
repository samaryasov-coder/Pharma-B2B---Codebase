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

        $out = array();
        foreach ($this->candidateTendersForSupplier((int) $company->id) as $tender) {
            if (!pb2bTenderPolicy::viewAsSupplier($tender, $company)) {
                continue;
            }
            $application = $this->findApplication((int) $tender->id, (int) $company->id);
            $out[] = array(
                'tender' => pb2bTenderResource::make($tender)->resolve(),
                'application' => $application
                    ? $this->serializeApplication($application, false)
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
    public function getNotice(int $tender_id, int $company_id): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->loadSupplierTender($tender_id, $company, 'viewAsSupplier');
        $application = $this->findApplication((int) $tender->id, (int) $company->id);

        return array(
            'tender' => pb2bTenderResource::make($tender)->resolve(),
            'items' => $this->serializeNoticeItems($tender),
            'documents' => $this->serializeNoticeDocuments($tender),
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
        $tender = $this->loadSupplierTender($tender_id, $company, 'apply');
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);

        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if ($application) {
            if ($application->getStatusCode() === pb2bTenderApplication::STATUS_WITHDRAWN) {
                throw new waException('Заявка отозвана', pb2bHttpStatus::CONFLICT);
            }
            $this->assertApplicationPolicy($application, $company, 'view');
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
        $draft = $this->getOrCreateDraft($tender_id, $company_id);
        $application = $this->getApplicationWithAssert((int) ($draft['application']['id'] ?? 0));
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->getTenderWithAssert($tender_id);
        $this->assertApplicationPolicy($application, $company, 'update');
        $this->assertEditable($application, $tender);

        if (array_key_exists('items', $payload)) {
            $this->replaceItems($application, $tender, (array) $payload['items']);
        }
        if (array_key_exists('criteria', $payload)) {
            $this->replaceCriteria($application, $tender, (array) $payload['criteria']);
        }
        if (array_key_exists('documents', $payload)) {
            $this->replaceDocuments($application, (array) $payload['documents']);
        }

        $patch = $this->applicationRow($application);
        if (array_key_exists('nonprice_done', $payload)) {
            $patch['nonprice_done'] = !empty($payload['nonprice_done']) ? 1 : 0;
        } elseif ($this->mandatoryCriteriaAnswered($application, $tender)) {
            $patch['nonprice_done'] = 1;
        }
        $save = $application->save($patch);
        if (!empty($save['error'])) {
            $this->throwSaveError($save, 'Не удалось сохранить заявку');
        }

        return $this->supplierApplicationPayload($tender, $application);
    }

    /**
     * @return array{tender: array, application: array, gates: array}
     * @throws waException
     */
    public function submit(int $tender_id, int $company_id): array
    {
        $company = $this->getSupplierCompanyWithAssert($company_id);
        $tender = $this->loadSupplierTender($tender_id, $company, 'apply');
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);

        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if (!$application) {
            throw new waException('Сначала сохраните заявку', pb2bHttpStatus::CONFLICT);
        }
        $this->assertApplicationPolicy($application, $company, 'submit');

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
        $tender = $this->loadSupplierTender($tender_id, $company, 'apply');
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);

        $application = $this->findApplication((int) $tender->id, (int) $company->id);
        if (!$application) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        $this->assertApplicationPolicy($application, $company, 'withdraw');

        if (!$application->canWithdraw()) {
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
        $this->getOrCreateDraft($tender_id, $company_id);
        if (!$upload_file->uploaded()) {
            throw new waException('Файл не загружен', pb2bHttpStatus::BAD_REQUEST);
        }

        $ext = strtolower((string) $upload_file->extension);
        $allowed = array('pdf', 'doc', 'docx');
        if (!in_array($ext, $allowed, true)) {
            throw new waException('Допустимые форматы: PDF, DOC, DOCX', pb2bHttpStatus::BAD_REQUEST);
        }
        if ((int) $upload_file->size > 10 * 1024 * 1024) {
            throw new waException('Максимальный размер файла: 10 МБ', pb2bHttpStatus::BAD_REQUEST);
        }

        $file_link = $this->fileStorageService->saveFileAndCreateLink(
            $upload_file,
            self::FILE_DIR_NAME,
            $company_id
        );

        return array(
            'file_link_id' => (int) $file_link->id,
            'filename' => (string) ($file_link->data['filename'] ?? $upload_file->name),
            'size' => (int) $upload_file->size,
            'ext' => $ext,
        );
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

        $include_prices = pb2bTenderApplicationResource::isBuyerPriceVisible($this->tenderStatusCode($tender));
        $applications = array();
        foreach ($this->applicationsForTender((int) $tender->id) as $application) {
            if ($application->getStatusCode() === pb2bTenderApplication::STATUS_DRAFT) {
                continue;
            }
            if ($application_id > 0 && (int) $application->id !== $application_id) {
                continue;
            }
            if (!pb2bTenderApplicationPolicy::viewAsBuyer($application, $company)) {
                continue;
            }
            $applications[] = $this->serializeApplication($application, $include_prices);
        }
        if ($application_id > 0 && $applications === array()) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }

        return array(
            'tender' => pb2bTenderResource::make($tender)->resolve(),
            'applications' => $applications,
        );
    }

    public function canAccessProposal(?pb2bTenderApplication $application, pb2bTender $tender): bool
    {
        $tender_row = $this->tenderRow($tender);
        $has_mandatory = $this->hasMandatoryCriteria($tender);
        $approval_required = !empty($tender_row['approval_required']);

        if (!$application) {
            return !$has_mandatory && !$approval_required;
        }

        $row = $this->applicationRow($application);
        $nonprice_ok = !empty($row['nonprice_done']) || !$has_mandatory;
        $approval_code = (string) ($row['approval_status'] ?? '');
        $qualification_code = (string) ($row['qualification_status'] ?? '');
        $approval_ok = in_array($approval_code, array('approved', 'not_required'), true)
            || !$approval_required;
        $qualification_ok = in_array($qualification_code, array('passed', 'not_required'), true);

        return $nonprice_ok && $approval_ok && $qualification_ok;
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
            pb2bTenderParticipationStep::NON_PRICE->value => true,
            pb2bTenderParticipationStep::APPROVAL->value => !empty($gates['nonprice_done']) && $approval_needed,
            pb2bTenderParticipationStep::QUALIFICATION->value => !empty($gates['nonprice_done']) && $qualification_needed,
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
        if (empty($gates['nonprice_done'])) {
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
            // nonprice_done=true и без обязательных критериев (gatePayload).
            // Пройденный шаг — completed (синий + галочка), иначе active/available.
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

    private function loadSupplierTender(int $tender_id, pb2bCompany $company, string $policy_method): pb2bTender
    {
        if (!in_array($policy_method, array('viewAsSupplier', 'apply'), true)) {
            throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        }
        $tender = $this->getTenderWithAssert($tender_id);
        $this->statusService->ensureReceptionOpen($tender);
        if (!pb2bTenderPolicy::{$policy_method}($tender, $company)) {
            throw new waException('Тендер не найден', pb2bHttpStatus::NOT_FOUND);
        }

        return $tender;
    }

    private function assertApplicationPolicy(
        pb2bTenderApplication $application,
        pb2bCompany $company,
        string $method
    ): void {
        if (!in_array($method, array('view', 'update', 'submit', 'withdraw'), true)) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        if (!pb2bTenderApplicationPolicy::{$method}($application, $company)) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
    }

    private function throwSaveError(array $result, string $fallback): void
    {
        throw new waException(
            (string) ($result['message'] ?? $fallback),
            pb2bHttpStatus::BAD_REQUEST
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
     *   requires_prequalification: int,
     *   display_budget: float
     * }
     */
    private function supplierListCard(pb2bTender $tender): array
    {
        $row = $this->tenderRow($tender);
        $organizer = '';
        $organizer_id = (int) ($row['organizer_company_id'] ?? 0);
        if ($organizer_id > 0) {
            $company = new pb2bCompany($organizer_id);
            if ((int) $company->id) {
                $organizer = trim($company->getFullName());
            }
        }

        $city = '';
        foreach ($tender->getItemsForView() as $item) {
            if (!is_array($item)) {
                continue;
            }
            $place = trim((string) ($item['delivery_place'] ?? ''));
            if ($place !== '') {
                $city = $place;
                break;
            }
        }

        $mnn = array();
        $category = '';
        $classifiers = (new pb2bTenderClassifierCollection())->getByTenderId((int) $tender->id);
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
            if ($code === 'category' && $category === '') {
                $category = $name;
            }
        }

        $budget = (float) ($row['budget'] ?? 0);
        $items_max_total = $tender->getItemsMaxTotal();
        $display_budget = $budget > 0 ? $budget : $items_max_total;

        return array(
            'organizer' => $organizer,
            'city' => $city,
            'category' => $category,
            'mnn' => array_values($mnn),
            'requires_prequalification' => (int) ($row['past_prequal_tender_id'] ?? 0) > 0 ? 1 : 0,
            'display_budget' => $display_budget,
        );
    }

    private function tenderRow(pb2bTender $tender): array
    {
        $data = $tender->data;

        return is_array($data) ? $data : array();
    }

    private function applicationRow(pb2bTenderApplication $application): array
    {
        $data = $application->data;

        return is_array($data) ? $data : array();
    }

    private function tenderStatusCode(pb2bTender $tender): string
    {
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $row = $this->tenderRow($tender);

        return (string) ($statuses[(int) ($row['status'] ?? 0)]['code'] ?? '');
    }

    private function assertReceptionOpen(pb2bTender $tender): void
    {
        if ($this->tenderStatusCode($tender) !== 'priem_zayavok') {
            throw new waException('Приём заявок закрыт', pb2bHttpStatus::CONFLICT);
        }
    }

    private function assertBeforeDeadline(pb2bTender $tender): void
    {
        $end_at = trim((string) ($this->tenderRow($tender)['end_at'] ?? ''));
        if ($end_at === '' || strpos($end_at, '0000-00-00') === 0) {
            return;
        }
        if (strtotime($end_at) <= time()) {
            throw new waException('Срок подачи заявок истёк', pb2bHttpStatus::CONFLICT);
        }
    }

    private function assertEditable(pb2bTenderApplication $application, pb2bTender $tender): void
    {
        $this->assertReceptionOpen($tender);
        $this->assertBeforeDeadline($tender);
        $status = $application->getStatusCode();
        if ($status === pb2bTenderApplication::STATUS_WITHDRAWN) {
            throw new waException('Отозванную заявку нельзя изменить', pb2bHttpStatus::CONFLICT);
        }
        if ($status === pb2bTenderApplication::STATUS_SUBMITTED) {
            if (!$application->canRevertToDraft()) {
                throw new waException('Заявку нельзя править в текущем статусе', pb2bHttpStatus::CONFLICT);
            }
            $result = $application->applyRevertToDraft();
            if (!empty($result['error'])) {
                throw new waException(
                    (string) ($result['message'] ?? 'Не удалось вернуть заявку в черновик'),
                    pb2bHttpStatus::CONFLICT
                );
            }
        }
    }

    private function findApplication(int $tender_id, int $supplier_company_id): ?pb2bTenderApplication
    {
        if ($tender_id <= 0 || $supplier_company_id <= 0) {
            return null;
        }
        $row = (new pb2bTenderApplicationModel())->getByField(array(
            'tender_id' => $tender_id,
            'supplier_company_id' => $supplier_company_id,
        ));
        $id = is_array($row) ? (int) ($row['id'] ?? 0) : 0;
        if ($id <= 0) {
            return null;
        }

        return new pb2bTenderApplication($id);
    }

    private function createDraft(pb2bTender $tender, pb2bCompany $company): pb2bTenderApplication
    {
        $has_mandatory = $this->hasMandatoryCriteria($tender);
        $approval_required = !empty($this->tenderRow($tender)['approval_required']);
        $application = new pb2bTenderApplication();
        $save = $application->save(array(
            'tender_id' => (int) $tender->id,
            'supplier_company_id' => (int) $company->id,
            'status' => pb2bTenderApplication::STATUS_DRAFT,
            'nonprice_done' => $has_mandatory ? 0 : 1,
            'approval_status' => $approval_required ? 'pending' : 'not_required',
            'qualification_status' => 'not_required',
            'admission_status' => 'pending',
        ));
        if (!empty($save['error']) || !(int) $application->id) {
            $this->throwSaveError($save, 'Не удалось создать заявку');
        }

        return $application;
    }

    /**
     * @return array{tender: array, application: array, gates: array}
     */
    private function supplierApplicationPayload(pb2bTender $tender, pb2bTenderApplication $application): array
    {
        return array(
            'tender' => pb2bTenderResource::make($tender)->resolve(),
            'application' => $this->serializeApplication($application, true),
            'gates' => $this->gatePayload($application, $tender),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeApplication(pb2bTenderApplication $application, bool $include_prices): array
    {
        $id = (int) $application->id;
        $items = (new pb2bTenderApplicationItemModel())->getByField('application_id', $id, true);
        $documents = (new pb2bTenderApplicationDocumentModel())->getByField('application_id', $id, true);
        $criteria = (new pb2bTenderApplicationCriterionModel())->getByField('application_id', $id, true);

        return pb2bTenderApplicationResource::make($application)
            ->withPrices($include_prices)
            ->withItems(is_array($items) ? array_values($items) : array())
            ->withDocuments(is_array($documents) ? array_values($documents) : array())
            ->withCriteria(is_array($criteria) ? array_values($criteria) : array())
            ->resolve();
    }

    /**
     * @return array{
     *   proposal: bool,
     *   nonprice_done: bool,
     *   approval_ok: bool,
     *   qualification_ok: bool,
     *   approval_status: string,
     *   qualification_status: string
     * }
     */
    private function gatePayload(?pb2bTenderApplication $application, pb2bTender $tender): array
    {
        $row = $application ? $this->applicationRow($application) : array();
        $has_mandatory = $this->hasMandatoryCriteria($tender);
        $approval_required = !empty($this->tenderRow($tender)['approval_required']);
        $approval_code = (string) ($row['approval_status'] ?? ($approval_required ? 'pending' : 'not_required'));
        $qualification_code = (string) ($row['qualification_status'] ?? 'not_required');
        if ($approval_code === '' && !$approval_required) {
            $approval_code = 'not_required';
        }
        if ($qualification_code === '') {
            $qualification_code = 'not_required';
        }

        return array(
            'proposal' => $this->canAccessProposal($application, $tender),
            'nonprice_done' => !empty($row['nonprice_done']) || !$has_mandatory,
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
        $hide_price = !empty($this->tenderRow($tender)['hide_initial_price']);
        $out = array();
        foreach ($tender->getItemsForView() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $file_link_id = (int) ($row['file_link_id'] ?? 0);
            $item = array(
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'qty' => $row['qty'] ?? null,
                'unit' => $row['unit'] ?? null,
                'vat_rate' => $row['vat_rate'] ?? null,
                'delivery_place' => $row['delivery_place'] ?? null,
                'comment' => $row['comment'] ?? null,
                'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                'file_name' => (string) ($row['file_name'] ?? ''),
                'sort' => (int) ($row['sort'] ?? 0),
            );
            if (!$hide_price) {
                $item['max_price_no_vat'] = $row['max_price_no_vat'] ?? null;
            }
            $out[] = $item;
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function serializeNoticeDocuments(pb2bTender $tender): array
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

    /**
     * @return list<array<string, mixed>>
     */
    private function serializeNoticeCriteria(pb2bTender $tender): array
    {
        $out = array();
        foreach (pb2bTender::getCriteriaForTender((int) $tender->id) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $out[] = array(
                'id' => (int) ($row['id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'type' => (string) ($row['type'] ?? 'non_price'),
                'is_mandatory' => !empty($row['is_mandatory']) ? 1 : 0,
                'description' => (string) ($row['description'] ?? ''),
            );
        }

        return $out;
    }

    private function hasMandatoryCriteria(pb2bTender $tender): bool
    {
        foreach (pb2bTender::getCriteriaForTender((int) $tender->id) as $row) {
            if (is_array($row) && !empty($row['is_mandatory'])) {
                return true;
            }
        }

        return false;
    }

    private function mandatoryCriteriaAnswered(pb2bTenderApplication $application, pb2bTender $tender): bool
    {
        $answers = array();
        $rows = (new pb2bTenderApplicationCriterionModel())->getByField(
            'application_id',
            (int) $application->id,
            true
        );
        foreach (is_array($rows) ? $rows : array() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $answers[(int) ($row['criterion_id'] ?? 0)] = $row;
        }
        foreach (pb2bTender::getCriteriaForTender((int) $tender->id) as $criterion) {
            if (!is_array($criterion) || empty($criterion['is_mandatory'])) {
                continue;
            }
            $answer = $answers[(int) ($criterion['id'] ?? 0)] ?? null;
            if (!is_array($answer)) {
                return false;
            }
            $value = trim((string) ($answer['value'] ?? ''));
            if ($value === '' && empty($answer['confirmed']) && (int) ($answer['file_link_id'] ?? 0) <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function replaceItems(pb2bTenderApplication $application, pb2bTender $tender, array $rows): void
    {
        $notice = array();
        foreach ($tender->getItems() as $item) {
            if (is_array($item) && (int) ($item['id'] ?? 0) > 0) {
                $notice[(int) $item['id']] = $item;
            }
        }
        $keep = array();
        $sort = 0;
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $tender_item_id = (int) ($row['tender_item_id'] ?? $row['id'] ?? 0);
            if ($tender_item_id <= 0 || !isset($notice[$tender_item_id])) {
                throw new waException('Неизвестная позиция извещения', pb2bHttpStatus::BAD_REQUEST);
            }
            $notice_item = $notice[$tender_item_id];
            $existing = (new pb2bTenderApplicationItemModel())->getByField(array(
                'application_id' => (int) $application->id,
                'tender_item_id' => $tender_item_id,
            ));
            $item = new pb2bTenderApplicationItem($this->existingId($existing));
            $save = $item->save(array(
                'application_id' => (int) $application->id,
                'tender_item_id' => $tender_item_id,
                'qty' => $row['qty'] ?? ($notice_item['qty'] ?? null),
                'unit' => $row['unit'] ?? ($notice_item['unit'] ?? null),
                'price_per_unit' => $row['price_per_unit'] ?? null,
                'vat_rate' => $row['vat_rate'] ?? ($notice_item['vat_rate'] ?? null),
                'sort' => (int) ($row['sort'] ?? $sort),
            ));
            if (!empty($save['error'])) {
                $this->throwSaveError($save, 'Не удалось сохранить цену');
            }
            $keep[] = (int) $item->id;
            $sort++;
        }
        $this->deleteMissingChildren(new pb2bTenderApplicationItemModel(), (int) $application->id, $keep);
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function replaceCriteria(pb2bTenderApplication $application, pb2bTender $tender, array $rows): void
    {
        $allowed = array();
        foreach (pb2bTender::getCriteriaForTender((int) $tender->id) as $criterion) {
            if (is_array($criterion) && (int) ($criterion['id'] ?? 0) > 0) {
                $allowed[(int) $criterion['id']] = true;
            }
        }
        $keep = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $criterion_id = (int) ($row['criterion_id'] ?? $row['id'] ?? 0);
            if ($criterion_id <= 0 || empty($allowed[$criterion_id])) {
                throw new waException('Неизвестный критерий', pb2bHttpStatus::BAD_REQUEST);
            }
            $existing = (new pb2bTenderApplicationCriterionModel())->getByField(array(
                'application_id' => (int) $application->id,
                'criterion_id' => $criterion_id,
            ));
            $object = new pb2bTenderApplicationCriterion($this->existingId($existing));
            $save = $object->save(array(
                'application_id' => (int) $application->id,
                'criterion_id' => $criterion_id,
                'value' => $row['value'] ?? null,
                'file_link_id' => $row['file_link_id'] ?? null,
                'confirmed' => !empty($row['confirmed']) ? 1 : 0,
            ));
            if (!empty($save['error'])) {
                $this->throwSaveError($save, 'Не удалось сохранить ответ на критерий');
            }
            $keep[] = (int) $object->id;
        }
        $this->deleteMissingChildren(new pb2bTenderApplicationCriterionModel(), (int) $application->id, $keep);
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function replaceDocuments(pb2bTenderApplication $application, array $rows): void
    {
        $keep = array();
        $sort = 0;
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $id = (int) ($row['id'] ?? 0);
            $object = new pb2bTenderApplicationDocument($id > 0 ? $id : null);
            if ($id > 0) {
                $existing_row = $this->applicationRowCompat($object);
                if ((int) ($existing_row['application_id'] ?? 0) !== (int) $application->id) {
                    throw new waException('Документ заявки не найден', pb2bHttpStatus::NOT_FOUND);
                }
            }
            $save = $object->save(array(
                'application_id' => (int) $application->id,
                'tender_document_id' => $row['tender_document_id'] ?? null,
                'name' => $row['name'] ?? '',
                'comment' => $row['comment'] ?? null,
                'file_link_id' => $row['file_link_id'] ?? null,
                'sort' => (int) ($row['sort'] ?? $sort),
            ));
            if (!empty($save['error'])) {
                $this->throwSaveError($save, 'Не удалось сохранить документ заявки');
            }
            $keep[] = (int) $object->id;
            $sort++;
        }
        $this->deleteMissingChildren(new pb2bTenderApplicationDocumentModel(), (int) $application->id, $keep);
    }

    /**
     * @param mixed $row
     */
    private function existingId($row): ?int
    {
        $id = is_array($row) ? (int) ($row['id'] ?? 0) : 0;

        return $id > 0 ? $id : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function applicationRowCompat(pb2bTenderApplicationDocument $document): array
    {
        $data = $document->data;

        return is_array($data) ? $data : array();
    }

    /**
     * @param list<int> $keep_ids
     */
    private function deleteMissingChildren(pb2bWaproModel $model, int $application_id, array $keep_ids): void
    {
        $rows = $model->getByField('application_id', $application_id, true);
        foreach (is_array($rows) ? $rows : array() as $row) {
            $id = (int) ($row['id'] ?? 0);
            if ($id > 0 && !in_array($id, $keep_ids, true)) {
                $model->deleteById($id);
            }
        }
    }

    private function assertPricesComplete(pb2bTenderApplication $application, pb2bTender $tender): void
    {
        $prices = array();
        $rows = (new pb2bTenderApplicationItemModel())->getByField(
            'application_id',
            (int) $application->id,
            true
        );
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
        $rows = (new pb2bTenderApplicationDocumentModel())->getByField(
            'application_id',
            (int) $application->id,
            true
        );
        foreach (is_array($rows) ? $rows : array() as $row) {
            if (!is_array($row)) {
                continue;
            }
            $requirement_id = (int) ($row['tender_document_id'] ?? 0);
            if ($requirement_id > 0 && (int) ($row['file_link_id'] ?? 0) > 0) {
                $uploaded[$requirement_id] = true;
            }
        }
        foreach ($tender->getDocuments(pb2bTenderDocument::KIND_REQUIREMENT) as $doc) {
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
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'code');
        $priem_id = (int) ($statuses['priem_zayavok']['id'] ?? 0);
        $published_id = (int) ($statuses['opublikovan']['id'] ?? 0);

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
                 WHEN status = i:priem_id THEN 0
                 WHEN status = i:published_id THEN 1
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
                'priem_id' => $priem_id,
                'published_id' => $published_id,
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
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'code');
        $published_id = (int) ($statuses['opublikovan']['id'] ?? 0);
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

    /**
     * @return list<pb2bTenderApplication>
     */
    private function applicationsForTender(int $tender_id): array
    {
        $rows = (new pb2bTenderApplicationModel())->getByField('tender_id', $tender_id, true);
        $out = array();
        foreach (is_array($rows) ? $rows : array() as $row) {
            $id = (int) ($row['id'] ?? 0);
            if ($id > 0) {
                $out[] = new pb2bTenderApplication($id);
            }
        }

        return $out;
    }
}
