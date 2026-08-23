<?php

class pb2bDocflowTemplateDefaultAddUploadController extends waJsonController
{
    public function execute(): void
    {
        $this->response = pb2bDocflowTemplate::addDefaultFromUpload('file');
    }
}
