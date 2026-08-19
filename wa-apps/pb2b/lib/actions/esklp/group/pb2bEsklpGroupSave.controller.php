<?php

class pb2bEsklpGroupSaveController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $group = new pb2bEsklpGroup(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $group->save(waRequest::post('data', array()));
    }
}