<?php

class pb2bSettingsDocflowDefaultDeleteController extends waJsonController
{
    public function execute(): void
    {
        $this->response = pb2bDocflowDefaults::deleteFile(
            waRequest::post('file_id', null, waRequest::TYPE_INT)
        );
    }
}
