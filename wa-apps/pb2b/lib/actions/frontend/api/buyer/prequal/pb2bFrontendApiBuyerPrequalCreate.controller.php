<?php

class pb2bFrontendApiBuyerPrequalCreateController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        if (waRequest::method() !== 'post') {
            throw new waException('Создание ПК требует POST', pb2bHttpStatus::METHOD_NOT_ALLOWED);
        }
        $dto = new pb2bPrequalDto(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
        $prequal = (new pb2bPrequalService())->createFromBuyer($this->context->company(), $dto, wa()->getUser());

        $this->response = array('error' => false, 'item' => pb2bPrequalResource::make($prequal)->resolve());
    }
}
