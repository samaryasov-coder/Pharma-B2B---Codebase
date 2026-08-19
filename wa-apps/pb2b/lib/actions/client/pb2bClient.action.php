<?php

class pb2bClientAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waDbException
     * @throws waException
     */
    public function execute(): void
    {
        // $this->view->assign(array(
        //     'hash' => pb2bCompanyCollection::getFilterHash(waRequest::post('filters', array(), waRequest::TYPE_ARRAY))
        // ));
    }
}