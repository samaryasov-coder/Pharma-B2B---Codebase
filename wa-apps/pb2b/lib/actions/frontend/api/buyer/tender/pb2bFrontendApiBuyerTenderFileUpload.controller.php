<?php

class pb2bFrontendApiBuyerTenderFileUploadController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $result = $this->tenderService()->uploadFileFromBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            waRequest::file('file')
        );

        $this->response = [
            'error' => false,
            'message' => 'Файл загружен',
            'tender_id' => $tender_id,
            'file_link_id' => (int) ($result['file_link_id'] ?? 0),
            'filename' => (string) ($result['filename'] ?? ''),
            'size' => (int) ($result['size'] ?? 0),
            'ext' => (string) ($result['ext'] ?? ''),
        ];
    }
}
