<?php

class pb2bCategoryEditAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $category = new pb2bCategory(waRequest::get('id', null, waRequest::TYPE_INT));
        $this->view->assign($category->get());
    }
}