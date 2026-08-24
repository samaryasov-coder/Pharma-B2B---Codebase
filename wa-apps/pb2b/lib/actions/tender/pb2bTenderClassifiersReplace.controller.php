<?php

class pb2bTenderClassifiersReplaceController extends waJsonController
{
    public function execute(): void
    {
        if (waRequest::method() !== 'post') {
            $this->errors[] = 'Ожидался метод POST';
            return;
        }

        $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $rows = waRequest::post('classifiers', array(), waRequest::TYPE_ARRAY);

        $tender = new pb2bTender($tender_id);
        if (!$tender->id) {
            $this->response = array('error' => true, 'message' => 'Тендер не найден');
            return;
        }

        $this->response = $tender->replaceClassifiers($rows);
    }
}
