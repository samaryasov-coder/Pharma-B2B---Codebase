<?php

class pb2bFrontendApiBuyerPrequalCreateController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        $data = waRequest::post('data', array(), waRequest::TYPE_ARRAY);
        foreach (array('invitations', 'category_ids', 'documents', 'items') as $field) {
            if (!array_key_exists($field, $data)) continue;
            if ($data[$field] === '') $data[$field] = array();
            if (!is_array($data[$field])) throw new waException('Неверный список ПК: '.$field, pb2bHttpStatus::BAD_REQUEST);
        }
        if (array_key_exists('validity_months', $data) && $data['validity_months'] === '') $data['validity_months'] = null;
        $dto = new pb2bPrequalDto($data);
        $prequal = (new pb2bPrequalService())->createFromBuyer($this->context->company(), $dto, wa()->getUser());

        $this->response = array('error' => false, 'item' => pb2bPrequalResource::make($prequal)->resolve(), 'documents' => $prequal->getDocuments(), 'items' => $prequal->getItems());
    }
}
