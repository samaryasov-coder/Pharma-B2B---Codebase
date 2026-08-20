<?php
class pb2bDocflowTemplateService extends pb2bBaseService
{
    public const FILE_DIR_NAME = 'docflow_template';
    protected pb2bDocflowTemplateModel $docflowTemplateModel;
    protected pb2bFileStorageService $fileStorageService;

    protected function getTemplateItemWithAssert(int $item_id): pb2bDocflowTemplateItem
    {
        $template_item = new pb2bDocflowTemplateItem($item_id);
        if (!$template_item->id)
            throw new waException('Элемент шаблона не найден', pb2bHttpStatus::NOT_FOUND);

        return $template_item;
    }

    public function __construct(){
        $this->docflowTemplateModel = new pb2bDocflowTemplateModel();
        $this->fileStorageService = new pb2bFileStorageService();
    }

    /**
     * Возвращает найденный или создает новый шаблон по типу процесса
     */
    public function getOrCreateTemplate(int $process_type, int $company_id): ?pb2bDocflowTemplate
    {
        $template_id = $this->docflowTemplateModel::getOrCreateTemplateByProcessType($process_type, $company_id);
        if (empty($template_id))
            return null;

        return new pb2bDocflowTemplate($template_id);
    }

    /**
     * @throws waException
     */
    public function getTemplateItem(int $item_id, int $company_id): pb2bDocflowTemplateItem
    {
        $company = $this->getCompanyWithAssert($company_id);
        $template_item = $this->getTemplateItemWithAssert($item_id);
        if (!pb2bDocflowTemplateItemPolicy::view($template_item, $company))
            throw new waException('Элемент шаблона не доступен', pb2bHttpStatus::FORBIDDEN);

        return $template_item;
    }

    /**
     * @throws waException
     */
    public function addItem(pb2bDocflowTemplateItemDto $item_dto, int $template_process_type, int $company_id): ?pb2bDocflowTemplateItem
    {
        $company = $this->getCompanyWithAssert($company_id);
        $template = $this->getOrCreateTemplate($template_process_type, $company->id);
        if (!$template)
            throw new waException('Ошибка создания шаблона', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

        $template_item = new pb2bDocflowTemplateItem();
        $template_item->save($item_dto->merge(['template_id' => $template->id]));

        if ($item_dto->file->uploaded()) {
            $file_link = $this->fileStorageService->saveFileAndCreateLink($item_dto->file, $this::FILE_DIR_NAME, $company->id);
            $template_item->save(['file_link_id' => $file_link->id]);
        }

        return $template_item;
    }

    /**
     * @throws waException
     */
    public function updateItem(int $item_id, pb2bDocflowTemplateItemDto $item_dto, int $template_process_type, int $company_id): pb2bDocflowTemplateItem
    {
        $company = $this->getCompanyWithAssert($company_id);
        $template_item = $this->getTemplateItemWithAssert($item_id);

        if (!pb2bDocflowTemplateItemPolicy::update($template_item, $template_process_type, $company))
            throw new waException('Элемент шаблона не доступен', pb2bHttpStatus::FORBIDDEN);

        if ($item_dto->file->uploaded()){
            $file_link = $template_item->getFileLink();
            if (!$file_link){
                $file_link = $this->fileStorageService->saveFileAndCreateLink($item_dto->file, $this::FILE_DIR_NAME, $company->id);
                $template_item->save(['file_link_id' => $file_link->id]);
            }
            else {
                $file = $this->fileStorageService->getOrCreateFile($item_dto->file, $this::FILE_DIR_NAME);
                if ($file)
                    $file_link->updateFile($file, $item_dto->file->name);
            }
        }
        $template_item->save($item_dto->toArray());

        return $template_item;
    }

    /**
     * @throws waException
     */
    public function downloadItemFile(int $item_id, int $template_process_type, int $company_id): void
    {
        $company = $this->getCompanyWithAssert($company_id);
        $template_item = $this->getTemplateItemWithAssert($item_id);

        if (!pb2bDocflowTemplateItemPolicy::downloadFile($template_item, $template_process_type, $company))
            throw new waException('Файл шаблона не доступен', pb2bHttpStatus::FORBIDDEN);

        $file_link = $template_item->getFileLink();
        $file = $file_link?->getFile();
        if (!$file)
            throw new waException('Не удалось найти файл', pb2bHttpStatus::NOT_FOUND);

        $path = pb2bStorage::disk($file->data['storage_disk'])->path($file->data['storage_path']);
        waFiles::readFile($path, $file_link->data['filename']);
    }

    /**
     * @throws waException
     */
    public function deleteItem(int $item_id, int $template_process_type, int $company_id): bool
    {
        $company = $this->getCompanyWithAssert($company_id);
        $template_item = $this->getTemplateItemWithAssert($item_id);

        if (!pb2bDocflowTemplateItemPolicy::delete($template_item, $template_process_type, $company))
            throw new waException('Элемент шаблона не доступен', pb2bHttpStatus::FORBIDDEN);

        $template_item->delete();

        return true;
    }
}