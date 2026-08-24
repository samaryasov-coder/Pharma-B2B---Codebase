<?php

class pb2bEsklpSaveController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $esklp = new pb2bEsklp(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $esklp->save(waRequest::post('data', array()));
    }
}