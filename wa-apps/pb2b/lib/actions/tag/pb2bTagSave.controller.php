<?php

class pb2bTagSaveController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $tag = new pb2bTag(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $tag->save(waRequest::post('data', array(), waRequest::TYPE_ARRAY));
    }
}