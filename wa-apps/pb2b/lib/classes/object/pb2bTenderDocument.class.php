<?php

class pb2bTenderDocument extends pb2bWaproObject
{
    public const KIND_TECH_SPEC = 'tech_spec';
    public const KIND_REQUIREMENT = 'requirement';

    private const ALLOWED_KINDS = array(
        self::KIND_TECH_SPEC,
        self::KIND_REQUIREMENT,
    );

    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_document';
        $this->model = new pb2bTenderDocumentModel();
        parent::__construct($id);
    }

    public static function isAllowedKind(string $kind): bool
    {
        return in_array($kind, self::ALLOWED_KINDS, true);
    }

    protected function preSave(array &$data): array
    {
        $data['tender_id'] = (int) ($data['tender_id'] ?? 0);
        $data['kind'] = trim((string) ($data['kind'] ?? ''));
        $data['name'] = trim((string) ($data['name'] ?? ''));
        $data['is_required'] = !empty($data['is_required']) ? 1 : 0;
        $data['sort'] = (int) ($data['sort'] ?? 0);

        if ($data['tender_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }
        if (!self::isAllowedKind($data['kind'])) {
            return array('error' => true, 'message' => 'Неверный тип документа тендера');
        }
        if ($data['name'] === '') {
            return array('error' => true, 'message' => 'Укажите название документа');
        }

        return parent::preSave($data);
    }
}
