<?php

class pb2bTenderAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        $this->view->assign(array(
            'hash' => pb2bTenderCollection::getFilterHash(waRequest::post('tender', array(), waRequest::TYPE_ARRAY)),
        ));
    }
}
