<?php
class pb2bDocflowRequestService extends pb2bBaseService
{
    public const FILE_DIR_NAME = 'docflow_request';
    protected pb2bDocflowTemplateModel $docflowTemplateModel;
    protected pb2bDocflowTemplateService $docflowTemplateService;
    protected pb2bFileStorageService $fileStorageService;

    private function getListByRole(string $role, int $company_id)
    {
        $collection = new pb2bDocflowRequestCollection("items.{$role}_company_id=$company_id");

        return $collection->getCollection([
            'key' => false,
            'select' => [
                'id' => null,
                'procedure_code' => null,
                'status' => null,
                'create_datetime' => null,
                'expires_datetime' => null,
                'reviewer_company_id' => null,
                'provider_company_id' => null,
            ],
        ]);
    }

    private function getReviewerCompanyWithAssert(int $company_id): pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        if (!$company->id || !$company->isBuyer())
            throw new waException('Не найден инициатор запроса - компания-покупатель', pb2bHttpStatus::NOT_FOUND);

        return $company;
    }

    private function getProviderCompanyWithAssert(int $company_id): pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        if (!$company->id || !$company->isSupplier())
            throw new waException('Не найдена компания-поставщик', pb2bHttpStatus::NOT_FOUND);

        return $company;
    }

    private function getRequestWithAssert(int $request_id): pb2bDocflowRequest
    {
        $request = new pb2bDocflowRequest($request_id);
        if (!$request->id)
            throw new waException('Не найден запрос', pb2bHttpStatus::NOT_FOUND);

        return $request;
    }

    public function getRequestItemWithAssert(int $request_item_id): pb2bDocflowRequestItem
    {
        $request_item = new pb2bDocflowRequestItem($request_item_id);
        if (!$request_item->id)
            throw new waException('Элемент запроса не найден', pb2bHttpStatus::NOT_FOUND);

        return $request_item;
    }

    private function checkItemsForUploadedWithAssert(pb2bDocflowRequest $request): void
    {
        $request_items = $request->getItems();
        foreach ($request_items as $request_item){
            if ($request_item->getStatus() !== pb2bDocflowRequestItemStatus::UPLOADED)
                throw new waException('Не все элементы запросов имеют загруженные документы');
        }
    }

    private function addItem(pb2bDocflowRequest $request, pb2bDocflowTemplateItem $template_item): pb2bDocflowRequestItem
    {
        $file_link = $template_item->getFileLink();
        $reviewer_file_link = $file_link ? $this->fileStorageService->cloneLink($file_link) : null;

        $request_item = new pb2bDocflowRequestItem();
        $result_save = $request_item->save([
            'request_id' => $request->id,
            'reviewer_name' => trim((string) ($template_item->data['name'] ?? '')),
            'reviewer_comment' => trim((string) ($template_item->data['comment'] ?? '')),
            'reviewer_file_link_id' => $reviewer_file_link?->id,
            'status' => pb2bDocflowRequestItemStatus::WAITING_PROVIDER->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);

        if ($result_save['error']){
            waLog::log($result_save, 'validate.log');
            throw new waException('Ошибка валидации элемента запроса', pb2bHttpStatus::BAD_REQUEST);
        }

        return $request_item;
    }



    public function __construct(){
        $this->docflowTemplateModel = new pb2bDocflowTemplateModel();
        $this->docflowTemplateService = new pb2bDocflowTemplateService();
        $this->fileStorageService = new pb2bFileStorageService();
    }


    /**
     * @throws waException
     */
    public function createFromReviewer(int $reviewer_company_id, int $provider_company_id, int $process_type, ?string $comment = null, ?array $template_item_ids = null): pb2bDocflowRequest
    {
        $reviewer_company = $this->getReviewerCompanyWithAssert($reviewer_company_id);
        $provider_company = $this->getProviderCompanyWithAssert($provider_company_id);
        if ($reviewer_company->id == $provider_company->id)
            throw new waException('Компания-инициатор и компания-получатель не могут совпадать', pb2bHttpStatus::BAD_REQUEST);

        $reviewer_template = $this->docflowTemplateService->getOrCreateTemplate($process_type, $reviewer_company->id);
        if (!$reviewer_template)
            throw new waException('Ошибка создания шаблона для компании-инициатора', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

        $reviewer_template_items = $reviewer_template->getItemsByCompanyType($provider_company->getType());
        if (empty($reviewer_template_items))
            throw new waException('В шаблоне отсутствуют элементы для компании-поставщика', pb2bHttpStatus::NOT_FOUND);

        if (!is_null($template_item_ids)){
            $template_item_ids = array_map('intval', $template_item_ids);
            $available_item_ids = array_map(static fn(pb2bDocflowTemplateItem $item) => (int) $item->id, $reviewer_template_items);
            $invalid_item_ids = array_diff($template_item_ids, $available_item_ids);

            if (!empty($invalid_item_ids))
                throw new waException(sprintf('Указанные элементы отсутствуют в шаблоне или недоступны для данного типа компании: %s', implode(', ', $invalid_item_ids)), pb2bHttpStatus::BAD_REQUEST);

            $reviewer_template_items = array_filter($reviewer_template_items, static fn(pb2bDocflowTemplateItem $item) => in_array((int) $item->id, $template_item_ids, true));
        }

        $request = new pb2bDocflowRequest();
        $result_save = $request->save([
            'process_type' => $process_type,
            'reviewer_company_id' => (int) $reviewer_company->id,
            'provider_company_id' => (int) $provider_company->id,
            'template_id' => $reviewer_template->id,
            'status' => pb2bDocflowRequestStatus::WAITING_PROVIDER->value,
            'status_datetime' => date('Y-m-d H:i:s'),
            'comment' => trim($comment) ?: null,
            'contact_id' => $reviewer_company->getContact()?->getId(),
        ]);
        if (!empty($result_save['error']))
            throw new waException($result_save['message'] ?? 'Не удалось создать запрос', pb2bHttpStatus::BAD_REQUEST);

        $this->createHistoryForRequest($request, null);

        foreach ($reviewer_template_items as $item)
            $this->addItem($request, $item);

        return $request;
    }

    /**
     * @throws waException
     */
    public function cancelFromReviewer(int $request_id, int $reviewer_company_id): pb2bDocflowRequest
    {
        $request = $this->getRequestWithAssert($request_id);
        $reviewer_company = $this->getReviewerCompanyWithAssert($reviewer_company_id);

        if (!pb2bDocflowRequestPolicy::cancelFromReviewer($request, $reviewer_company))
            throw new waException('Запрос не доступен', pb2bHttpStatus::FORBIDDEN);

        if (!$request->canCancelFromReviewer())
            throw new waException('Запрос нельзя отменять в текущем статусе', pb2bHttpStatus::BAD_REQUEST);

        $old_status = $request->getStatus();
        $request->applyCancel();
        $this->createHistoryForRequest($request, $old_status);

        return $request;
    }


    public function approveFromReviewer(int $request_id, int $reviewer_company_id): pb2bDocflowRequest
    {
        $request = $this->getRequestWithAssert($request_id);
        $reviewer_company = $this->getReviewerCompanyWithAssert($reviewer_company_id);

        if (!pb2bDocflowRequestPolicy::approveFromReviewer($request, $reviewer_company))
            throw new waException('Запрос не доступен', pb2bHttpStatus::FORBIDDEN);

        if (!$request->canApproveFromReviewer())
            throw new waException('Запрос нельзя утвердить в текущем статусе', pb2bHttpStatus::BAD_REQUEST);

        $old_status = $request->getStatus();
        $request->applyApprove();
        $this->createHistoryForRequest($request, $old_status);

        return $request;
    }


    public function uploadItemFromProvider(int $request_item_id, waRequestFile $file, $provider_company_id, ?string $provider_comment = null): pb2bDocflowRequestItem
    {
        $request_item = $this->getRequestItemWithAssert($request_item_id);
        $company = $this->getProviderCompanyWithAssert($provider_company_id);

        if (!pb2bDocflowRequestItemPolicy::uploadFromProvider($request_item, $company))
            throw new waException('Элемент запроса не доступен', pb2bHttpStatus::FORBIDDEN);

        if (!$request_item->canUploadFromProvider())
            throw new waException('Элемент запроса нельзя загружать в текущем статусе', pb2bHttpStatus::BAD_REQUEST);

        if (!$file->uploaded())
            throw new waException('Файл элемента запроса не загружен', pb2bHttpStatus::BAD_REQUEST);

        $file_link = $this->fileStorageService->saveFileAndCreateLink($file, $this::FILE_DIR_NAME, $company->id);
        $request_item->applyUpload($file_link, $provider_comment);

        return $request_item;
    }


    public function submitFromProvider(int $request_id, int $provider_company_id): pb2bDocflowRequest
    {
        $request = $this->getRequestWithAssert($request_id);
        $provider_company = $this->getProviderCompanyWithAssert($provider_company_id);

        if (!pb2bDocflowRequestPolicy::submitFromProvider($request, $provider_company))
            throw new waException('Запрос не доступен', pb2bHttpStatus::FORBIDDEN);

        if (!$request->canSubmitFromProvider())
            throw new waException('Запрос нельзя отправлять в текущем статусе', pb2bHttpStatus::BAD_REQUEST);

        $this->checkItemsForUploadedWithAssert($request);

        $old_status = $request->getStatus();
        $request->applySubmit();
        $this->createHistoryForRequest($request, $old_status);

        return $request;
    }


    public function revokeFromProvider(int $request_id, int $provider_company_id)
    {
        $request = $this->getRequestWithAssert($request_id);
        $provider_company = $this->getProviderCompanyWithAssert($provider_company_id);

        if (!pb2bDocflowRequestPolicy::revokeFromProvider($request, $provider_company))
            throw new waException('Запрос не доступен', pb2bHttpStatus::FORBIDDEN);

        if (!$request->canRevokeFromProvider())
            throw new waException('Запрос нельзя отзывать в текущем статусе', pb2bHttpStatus::BAD_REQUEST);

        $old_status = $request->getStatus();
        $request->applyRevoke();
        $this->createHistoryForRequest($request, $old_status);

        return $request;
    }


    /**
     * @throws waException
     */
    public function downloadItemReviewerFile(int $request_item_id, int $company_id): void
    {
        $request_item = $this->getRequestItemWithAssert($request_item_id);
        $company = $this->getCompanyWithAssert($company_id);

        if (!pb2bDocflowRequestItemPolicy::downloadReviewerFile($request_item, $company))
            throw new waException('Файл шаблона в запросе не доступен', pb2bHttpStatus::FORBIDDEN);

        $file_link = $request_item->getReviewerFileLink();
        $file = $file_link?->getFile();
        if (!$file)
            throw new waException('Не удалось найти файл', pb2bHttpStatus::NOT_FOUND);

        $path = pb2bStorage::disk($file->data['storage_disk'])->path($file->data['storage_path']);
        waFiles::readFile($path, $file_link->data['filename']);
    }


    // FIXME: Удаляет заявку, проверить доступы
    public function deleteRequest(int $request_id): bool
    {
        $request = $this->getRequestWithAssert($request_id);
        $request->delete();

        return true;
    }




    public function getListByReviewer(int $company_id)
    {
        return $this->getListByRole('reviewer', $company_id);
    }

    public function getListByProvider(int $company_id)
    {
        return $this->getListByRole('provider', $company_id);
    }

    public function getItemList(int $request_id){
        $collection = new pb2bDocflowRequestCollection(
            "ItemsWithItems.id=$request_id&ItemsWithItems.process_type=1"
        );
        $rows = $collection->getCollection([
            'key' => false,
            'select' => [
                'id' => null,
                ['field' => 'id', 'table' => 'DRI', 'as' => 'item_id'],
                ['field' => 'status', 'table' => 'DRI', 'as' => 'item_status'],
                ['field' => 'reviewer_name', 'table' => 'DRI', 'as' => 'item_reviewer_name'],
                ['field' => 'reviewer_comment', 'table' => 'DRI', 'as' => 'item_reviewer_comment'],
                ['field' => 'provider_comment', 'table' => 'DRI', 'as' => 'item_provider_comment'],
                ['field' => 'reviewer_file_link_id', 'table' => 'DRI', 'as' => 'item_reviewer_file_link_id'],
                ['field' => 'provider_file_link_id', 'table' => 'DRI', 'as' => 'item_provider_file_link_id'],
            ],
        ]);

        return $rows;
    }

    public function rejectFromReviewer(int $request_id, int $company_id, array $item_reasons){
        $request = new pb2bDocflowRequest($request_id);
        return $request->rejectFromReviewer($company_id, null, $item_reasons);
    }


}