<?php

class pb2bDocflowTemplateDefaultDeleteController extends waJsonController
{
    public function execute(): void
    {
        $this->response = pb2bDocflowTemplate::deleteDefaultFile(
            waRequest::post('file_id', null, waRequest::TYPE_INT)
        );
    }
}
