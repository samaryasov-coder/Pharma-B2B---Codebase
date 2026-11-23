<?php

class pb2bFrontendApiBuyerPrequalFileUploadController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        if (waRequest::method() !== 'post') throw new waException('Загрузка файла требует POST', pb2bHttpStatus::METHOD_NOT_ALLOWED);
        $this->response = (new pb2bPrequalService())->uploadFileFromBuyer(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            $this->context->company(),
            waRequest::file('file')
        );
    }
}
