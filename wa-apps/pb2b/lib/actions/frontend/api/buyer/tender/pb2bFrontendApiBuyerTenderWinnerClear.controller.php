<?php

/**
 * POST api/buyer/tender/<id>/winner/clear/
 * Сброс черновика выбора победителя.
 */
class pb2bFrontendApiBuyerTenderWinnerClearController extends pb2bFrontendCabinetController
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

        $state = $this->applicationService()->clearWinnerDraft(
            $tender_id,
            $this->tenderCompanyId()
        );

        $this->response = $this->ok($state, 'Выбор победителя сброшен');
    }
}
