<?php

class pb2bFrontendApiSupplierPrequalFileController extends pb2bFrontendCabinetController
{
    protected function executeSupplier(): void
    {
        (new pb2bPrequalService())->downloadDocumentFromSupplier(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            waRequest::param('file_link_id', 0, waRequest::TYPE_INT),
            $this->context->company()
        );
        exit;
    }
}
