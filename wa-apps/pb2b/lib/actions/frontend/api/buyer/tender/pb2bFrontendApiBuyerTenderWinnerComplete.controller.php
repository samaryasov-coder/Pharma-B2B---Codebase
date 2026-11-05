<?php

/**
 * POST api/buyer/tender/<id>/winner/complete/
 * Зафиксировать выбор: → summarizing_results + winner_selection_completed.
 */
class pb2bFrontendApiBuyerTenderWinnerCompleteController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        }
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $reason = waRequest::post('reason', null, waRequest::TYPE_STRING_TRIM);
        $result = $this->tenderService()->completeWinnerSelectionForBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            $reason !== '' && $reason !== null ? $reason : null,
            wa()->getUser()
        );

        $this->response = $this->ok($result, 'Выбор победителя завершён');
    }
}
