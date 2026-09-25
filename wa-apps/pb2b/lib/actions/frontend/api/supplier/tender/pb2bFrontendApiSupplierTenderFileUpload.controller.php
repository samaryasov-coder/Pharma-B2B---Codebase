<?php

class pb2bFrontendApiSupplierTenderFileUploadController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $tender_id = $this->requireTenderId();
        $result = $this->applicationService()->uploadFile(
            $tender_id,
            $this->supplierCompanyId(),
            waRequest::file('file')
        );

        $this->response = $this->ok(array(
            'tender_id' => $tender_id,
            'file_link_id' => (int) ($result['file_link_id'] ?? 0),
            'filename' => (string) ($result['filename'] ?? ''),
            'size' => (int) ($result['size'] ?? 0),
            'ext' => (string) ($result['ext'] ?? ''),
        ), 'Файл загружен');
    }
}
