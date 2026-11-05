<?php

class pb2bFrontendApiSupplierTenderDocumentsDownloadController extends pb2bFrontendCabinetController
{
    public function executeSupplier(): void
    {
        $scope = waRequest::get('scope', 'procurement', waRequest::TYPE_STRING_TRIM);
        (new pb2bTenderService())->downloadFilesForSupplier(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            $this->context->company(),
            waRequest::get($scope === 'item' ? 'item_id' : 'document_id', 0, waRequest::TYPE_INT),
            $scope
        );
        exit;
    }
}
