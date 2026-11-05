<?php

class pb2bTenderApplicationCriterion extends pb2bWaproObject
{
    public function __construct(?int $id = null)
    {
        $this->class_name = 'tender_application_criterion';
        $this->model = new pb2bTenderApplicationCriterionModel();
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $data['application_id'] = (int) ($data['application_id'] ?? 0);
        $data['criterion_id'] = (int) ($data['criterion_id'] ?? 0);
        $data['confirmed'] = !empty($data['confirmed']) ? 1 : 0;
        $file_link_id = (int) ($data['file_link_id'] ?? 0);
        $data['file_link_id'] = $file_link_id > 0 ? $file_link_id : null;
        $value = trim((string) ($data['value'] ?? ''));
        $data['value'] = $value !== '' ? $value : null;

        if ($data['application_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указана заявка');
        }
        if ($data['criterion_id'] <= 0) {
            return array('error' => true, 'message' => 'Не указан критерий');
        }

        $existing = $this->model->getByField(array(
            'application_id' => $data['application_id'],
            'criterion_id' => $data['criterion_id'],
        ));
        $existing_id = is_array($existing) ? (int) ($existing['id'] ?? 0) : 0;
        if ($existing_id > 0 && $existing_id !== (int) ($this->id ?? 0)) {
            return array('error' => true, 'message' => 'Ответ на этот критерий уже сохранён');
        }

        return parent::preSave($data);
    }
}
