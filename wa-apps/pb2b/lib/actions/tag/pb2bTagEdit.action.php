<?php

class pb2bTagEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $tag = new pb2bTag(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->view->assign($tag->get());
    }
}