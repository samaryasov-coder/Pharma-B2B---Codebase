<?php

class pb2bFrontendApiBuyerTenderCriterionSaveController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $criteria = waRequest::post('criteria', [], waRequest::TYPE_ARRAY);
        $result = $this->tenderService()->replaceCriteriaFromBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            is_array($criteria) ? $criteria : []
        );

        $this->response = [
            'error' => false,
            'message' => (string) ($result['message'] ?? 'Критерии сохранены'),
            'tender_id' => (int) ($result['tender_id'] ?? $tender_id),
            'count' => (int) ($result['count'] ?? 0),
        ];
    }
}
