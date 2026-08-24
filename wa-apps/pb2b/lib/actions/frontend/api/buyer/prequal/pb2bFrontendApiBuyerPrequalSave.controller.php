<?php

class pb2bFrontendApiBuyerPrequalSaveController extends pb2bFrontendCabinetController
{
    public function executeAction()
    {
        wa_dump(123);
        $company = $this->context->company();
        if (!$company || !$company->id) {
            $this->response = array('error' => true, 'message' => 'Компания не выбрана');
            return;
        }

        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $data = waRequest::post('data', array(), waRequest::TYPE_ARRAY);
        $wizard_extra = waRequest::post('wizard_extra', null, waRequest::TYPE_ARRAY);

        $data['reviewer_id'] = (int) $company->id;
        if (empty($data['owner_contact_id'])) {
            $data['owner_contact_id'] = (int) wa()->getUser()->getId();
        }

        if ($id) {
            $existing = new pb2bPrequal($id);
            if (!$existing->id) {
                $this->response = array('error' => true, 'message' => 'Процедура ПК не найдена');
                return;
            }
            if ((int) ($existing->data['reviewer_id'] ?? 0) !== (int) $company->id) {
                $this->response = array('error' => true, 'message' => 'Нет доступа к этой процедуре');
                return;
            }
        }

        $prequal = new pb2bPrequal($id ?: null);
        $payload = array(
            'data' => $data,
            'subjects' => waRequest::post('subjects', null, waRequest::TYPE_ARRAY),
            'positions' => waRequest::post('positions', null, waRequest::TYPE_ARRAY),
            'requirements' => waRequest::post('requirements', null, waRequest::TYPE_ARRAY),
        );
        if ($wizard_extra !== null) {
            $payload['wizard_extra'] = $wizard_extra;
        }

        $this->response = $prequal->saveWizard($payload);
    }
}
