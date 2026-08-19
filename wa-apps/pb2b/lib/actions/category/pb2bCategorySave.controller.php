<?php

class pb2bCategorySaveController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
        $category = new pb2bCategory(waRequest::post('id', 0, waRequest::TYPE_INT));
        $this->response = $category->save(waRequest::post('data', array()));
    }
}