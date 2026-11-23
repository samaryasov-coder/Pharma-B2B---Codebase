<?php

class pb2bFrontendApiSupplierPrequalDocumentsDownloadController extends pb2bFrontendCabinetController
{
    protected function executeSupplier(): void
    {
        (new pb2bPrequalService())->downloadDocumentsFromSupplier(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            $this->context->company()
        );
        exit;
    }
}
