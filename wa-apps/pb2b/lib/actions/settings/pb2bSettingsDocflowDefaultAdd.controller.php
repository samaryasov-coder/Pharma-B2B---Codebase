<?php

class pb2bSettingsDocflowDefaultAddController extends waJsonController
{
    public function execute(): void
    {
        $this->response = pb2bDocflowDefaults::addFromUpload('file');
    }
}
