<?php

class pb2bFrontendApiBuyerTenderClassifierSaveController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $rows = waRequest::post('classifiers', [], waRequest::TYPE_ARRAY);
        $result = $this->tenderService()->replaceClassifiersFromBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            is_array($rows) ? $rows : []
        );

        $this->response = [
            'error' => false,
            'message' => (string) ($result['message'] ?? 'Классификаторы сохранены'),
            'tender_id' => (int) ($result['tender_id'] ?? $tender_id),
            'count' => (int) ($result['count'] ?? 0),
        ];
    }
}
