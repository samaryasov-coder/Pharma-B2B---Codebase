<?php

class pb2bCategoryDeleteController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute(): void
    {
        $category = new pb2bCategory(waRequest::post('id', null, waRequest::TYPE_INT));
        $this->response = $category->delete();
    }
}