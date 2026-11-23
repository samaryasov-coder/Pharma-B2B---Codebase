<?php

class pb2bFrontendApiBuyerPrequalPublishController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        $prequal = (new pb2bPrequalService())->publishFromBuyer(waRequest::post('id', 0, waRequest::TYPE_INT), $this->context->company());
        $this->response = array('error' => false, 'message' => 'Предварительная квалификация опубликована', 'item' => pb2bPrequalResource::make($prequal)->resolve());
    }
}
