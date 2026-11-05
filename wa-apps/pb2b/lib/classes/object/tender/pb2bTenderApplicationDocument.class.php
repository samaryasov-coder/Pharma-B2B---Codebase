<?php

class pb2bTenderApplicationDocument extends pb2bWaproObject
{
    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_application_document';
        $this->model = new pb2bTenderApplicationDocumentModel();
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $data['application_id'] = (int) ($data['application_id'] ?? 0);
        $data['name'] = trim((string) ($data['name'] ?? ''));
        $data['sort'] = (int) ($data['sort'] ?? 0);
        $tender_document_id = (int) ($data['tender_document_id'] ?? 0);
        $data['tender_document_id'] = $tender_document_id > 0 ? $tender_document_id : null;
        $file_link_id = (int) ($data['file_link_id'] ?? 0);
        $data['file_link_id'] = $file_link_id > 0 ? $file_link_id : null;
        $comment = trim((string) ($data['comment'] ?? ''));
        $data['comment'] = $comment !== '' ? $comment : null;

        if ($data['application_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указана заявка');
        }
        if ($data['name'] === '') {
            return array('error' => true, 'message' => 'Укажите название документа');
        }

        return parent::preSave($data);
    }
}
