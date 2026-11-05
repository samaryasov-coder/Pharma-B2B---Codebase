<?php

class pb2bFrontendApiBuyerPrequalSaveController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        if (waRequest::method() !== 'post') {
            throw new waException('Сохранение ПК требует POST', pb2bHttpStatus::METHOD_NOT_ALLOWED);
        }
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $dto = new pb2bPrequalDto(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
        $prequal = (new pb2bPrequalService())->updateFromBuyer($id, $this->context->company(), $dto, wa()->getUser());

        $this->response = array('error' => false, 'item' => pb2bPrequalResource::make($prequal)->resolve());
    }
}
