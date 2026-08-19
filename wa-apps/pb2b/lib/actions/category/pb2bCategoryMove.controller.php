<?php

class pb2bCategoryMoveController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute(): void
    {
        $roadmap = new pb2bCategory(waRequest::post('id', 0, waRequest::TYPE_INT));
        $this->response = $roadmap->move(waRequest::post('parent_id', 0, waRequest::TYPE_INT), waRequest::post('before_id', 0, waRequest::TYPE_INT));
    }
}