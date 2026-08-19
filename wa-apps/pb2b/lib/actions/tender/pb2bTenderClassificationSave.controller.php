<?php

class pb2bTenderClassificationSaveController extends waJsonController
{
    public function execute(): void
    {
        if (waRequest::method() !== 'post') {
            $this->errors[] = 'Ожидался метод POST';
            return;
        }
        $obj = new pb2bTenderClassifier(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $obj->save(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
    }
}
