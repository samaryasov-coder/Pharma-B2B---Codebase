<?php

class pb2bTenderClassifier extends pb2bWaproObject
{
    private const ALLOWED_TYPES = array('esklp', 'okpd2');

    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_classifier';
        $this->model = new pb2bTenderClassifierModel();
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $data['tender_id'] = (int) ($data['tender_id'] ?? 0);
        $data['classifier_type'] = (int) ($data['classifier_type'] ?? 0);
        $data['classifier_id'] = (int) ($data['classifier_id'] ?? 0);

        if ($data['tender_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан тендер');
        }

        $tender = new pb2bTender($data['tender_id']);
        if (!$tender->id) {
            return array('error' => true, 'message' => 'Тендер не найден');
        }

        $type_check = self::validateClassifierTypeAndNode($data['classifier_type'], $data['classifier_id']);
        if ($type_check['error']) {
            return $type_check;
        }

        return parent::preSave($data);
    }

    public static function validateClassifierTypeAndNode(int $classifier_type, int $classifier_id): array
    {
        if ($classifier_id <= 0) {
            return array('error' => true, 'message' => 'Не указан узел классификатора');
        }

        $types_by_id = (array) pb2bWaproHelper::getConfigOption('tender_classifier_types', 'id');
        $type_row = $types_by_id[$classifier_type] ?? null;
        if (empty($type_row['code'])) {
            return array('error' => true, 'message' => 'Неверный тип классификатора');
        }

        $code = (string) $type_row['code'];
        if (!in_array($code, self::ALLOWED_TYPES, true)) {
            return array('error' => true, 'message' => 'Тип классификатора пока не поддерживается');
        }

        if ($code === 'esklp') {
            $esklp = new pb2bEsklp($classifier_id);
            if (!$esklp->id) {
                return array('error' => true, 'message' => 'Запись ЕСКЛП не найдена');
            }
            return array('error' => false);
        }

        if ($code === 'okpd2') {
            return self::validateOkpd2NodeExists($classifier_id);
        }

        return array('error' => false);
    }

    private static function validateOkpd2NodeExists(int $classifier_id): array
    {
        try {
            $model = new waModel();
            $row = $model->query(
                'SELECT id FROM pb2b_okpd2 WHERE id = ?',
                $classifier_id
            )->fetchAssoc();
            if (empty($row['id'])) {
                return array('error' => true, 'message' => 'Запись ОКПД2 не найдена');
            }
            return array('error' => false);
        } catch (Exception $e) {
            return array('error' => true, 'message' => 'Справочник ОКПД2 недоступен');
        }
    }
}
