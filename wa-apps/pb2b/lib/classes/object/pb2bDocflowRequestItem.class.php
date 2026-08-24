<?php

class pb2bDocflowRequestItem extends pb2bWaproObject
{
    protected function preDelete(array &$data = array()): array
    {
        $result = parent::preDelete($data);
        if ($result['error']) return $result;

        $this->getReviewerFileLink()?->delete();
        $this->getProviderFileLink()?->delete();

        return $result;
    }

    private function hasStatus(array $allowed): bool
    {
        return in_array($this->getStatus(), $allowed, true);
    }


    public function __construct(?int $id = null)
    {
        $this->model = new pb2bDocflowRequestItemsModel();
        parent::__construct($id);
    }

    /**
     * Возвращает привязанный запрос
     */
    public function getRequest(): ?pb2bDocflowRequest
    {
        $request = new pb2bDocflowRequest($this->data['request_id'] ?? 0);
        return $request->id ? $request : null;
    }

    /**
     * Возвращает статус элемента запроса
     */
    public function getStatus(): pb2bDocflowRequestItemStatus
    {
        return pb2bDocflowRequestItemStatus::from($this->data['status']);
    }

    /**
     * Возвращает ссылку на файл инициатора (покупателя)
     */
    public function getReviewerFileLink(): ?pb2bFileLink
    {
        $file_link = new pb2bFileLink($this->data['reviewer_file_link_id'] ?? 0);
        return $file_link->id ? $file_link : null;
    }

    /**
     * Возвращает ссылку на файл поставщика
     */
    public function getProviderFileLink(): ?pb2bFileLink
    {
        $file_link = new pb2bFileLink($this->data['provider_file_link_id'] ?? 0);
        return $file_link->id ? $file_link : null;
    }

    /**
     * Проверяет, может ли поставщик отправить элемент запроса
     */
    public function canUploadFromProvider(): bool
    {
        return $this->hasStatus([pb2bDocflowRequestItemStatus::WAITING_PROVIDER, pb2bDocflowRequestItemStatus::UPLOADED, pb2bDocflowRequestItemStatus::REJECTED]);
    }

    /**
     * Запускает процесс принятия
     */
    public function applyAccepted(): void
    {
        $this->save([
            'status' => pb2bDocflowRequestItemStatus::ACCEPTED->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Запускает процесс отправки на проверку
     */
    public function applySubmit(): void
    {
        $this->save([
            'status' => pb2bDocflowRequestItemStatus::WAITING_REVIEW->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Запускает процесс отзывания
     */
    public function applyRevoke(): void
    {
        $this->save([
            'status' => pb2bDocflowRequestItemStatus::UPLOADED->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Запускает процесс отмены
     */
    public function applyCancel(): void
    {
        $this->save([
            'status' => pb2bDocflowRequestItemStatus::CANCELLED->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Запускает процесс загрузки;
     *
     * Если ссылка на файл уже присутствует, то происходит её удаление и замена на новую ссылку;
     *
     * Если комментарий уже присутствует, то происходит изменение его на новый комментарий.
     */
    public function applyUpload(pb2bFileLink $provider_file_link, ?string $provider_comment = null): void
    {
        $this->getProviderFileLink()?->delete();

        $this->save([
            'provider_file_link_id' => $provider_file_link->id,
            'provider_comment' => is_null($provider_comment) ? null : trim($provider_comment),
            'status' => pb2bDocflowRequestItemStatus::UPLOADED->value,
            'status_datetime' => date('Y-m-d H:i:s'),
        ]);
    }




    /**
     * Возвращает все элементы переданного запроса
     *
     * @return pb2bDocflowRequestItem[]
     */
    static public function getByRequest(int $request_id): array
    {
        $rows = (new self())->model::getIdsByRequest($request_id);

        return array_map(fn(array $row) => new self((int)$row['id']), $rows);
    }

    /**
     * Удаляет все элементы переданного запроса
     */
    static public function deleteByRequest(int $request_id): void
    {
        $request_items = self::getByRequest($request_id);
        foreach ($request_items as $item) $item->delete();
    }

}
