<?php

class pb2bDocflowTemplateItem extends pb2bWaproObject
{
    protected function preDelete(array &$data = array()): array
    {
        $result = parent::preDelete($data);
        if ($result['error']) return $result;

        $this->getFileLink()?->delete();

        return $result;
    }


    public function __construct(?int $id = null)
    {
        $this->model = new pb2bDocflowTemplateItemsModel();
        parent::__construct($id);
    }

    /**
     * Возвращает привязанный шаблон
     */
    public function getTemplate(): ?pb2bDocflowTemplate
    {
        $template = new pb2bDocflowTemplate($this->data['template_id'] ?? 0);
        return $template->id ? $template : null;
    }

    /**
     * Возвращает ссылку на файл
     */
    public function getFileLink(): ?pb2bFileLink
    {
        $file_link = new pb2bFileLink($this->data['file_link_id'] ?? 0);
        return $file_link->id ? $file_link : null;
    }

    /**
     * Возвращает тип компании
     */
    public function getType(): pb2bCompanyType
    {
        return pb2bCompanyType::from($this->data['company_type']);
    }
}
