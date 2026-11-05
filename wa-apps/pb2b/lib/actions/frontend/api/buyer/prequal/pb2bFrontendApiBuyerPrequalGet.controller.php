<?php

class pb2bFrontendApiBuyerPrequalGetController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        $id = waRequest::get('id', 0, waRequest::TYPE_INT);
        $prequal = (new pb2bPrequalService())->getFromBuyer($id, $this->context->company());

        $this->response = array('error' => false, 'item' => pb2bPrequalResource::make($prequal)->resolve());
    }
}
