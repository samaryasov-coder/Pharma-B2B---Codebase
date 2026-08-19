<?php

class pb2bTenderSaveController extends waJsonController
{
    public function execute(): void
    {
        if (waRequest::method() !== 'post') {
            $this->errors[] = 'Ожидался метод POST';
            return;
        }
        $tender = new pb2bTender(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $tender->save(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
    }
}
