<?php

class pb2bClientSaveController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $client = new pb2bClient(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $client->save(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
    }
}