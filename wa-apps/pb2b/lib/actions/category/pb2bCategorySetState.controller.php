<?php

class pb2bCategorySetStateController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute(): void
    {
        $category = new pb2bCategory(waRequest::post('id', 0, waRequest::TYPE_INT));
        $category->setState(waRequest::post('state', 0, waRequest::TYPE_INT));
    }
}