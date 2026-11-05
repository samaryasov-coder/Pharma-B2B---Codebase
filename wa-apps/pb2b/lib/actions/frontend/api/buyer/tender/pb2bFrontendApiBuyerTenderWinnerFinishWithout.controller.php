<?php

/**
 * POST api/buyer/tender/<id>/winner/finish-without/
 * Завершить без победителя (reason обязателен).
 */
class pb2bFrontendApiBuyerTenderWinnerFinishWithoutController extends pb2bFrontendCabinetController
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

        $reason = waRequest::post('reason', '', waRequest::TYPE_STRING_TRIM);
        $result = $this->tenderService()->finishWithoutWinnerForBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            (string) $reason,
            wa()->getUser()
        );

        $this->response = $this->ok($result, 'Процедура завершена без победителя');
    }
}
