<?php

class pb2bTenderPublishController extends waJsonController
{
    public function execute(): void
    {
        if (waRequest::method() !== 'post') {
            $this->errors[] = 'Ожидался метод POST';
            return;
        }

        $tender = new pb2bTender(waRequest::post('id', 0, waRequest::TYPE_INT));
        if (!$tender->id) {
            $this->response = array('error' => true, 'message' => 'Тендер не найден');
            return;
        }

        $this->response = $tender->publish(waRequest::post('reason', null, waRequest::TYPE_STRING_TRIM));
    }
}
