<?php

/**
 * GET api/buyer/tender/<id>/winner/
 * Черновик / итог выбора победителя (после start-review).
 */
class pb2bFrontendApiBuyerTenderWinnerGetController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $state = $this->applicationService()->getWinnerStateForBuyer(
            $tender_id,
            $this->tenderCompanyId()
        );

        $this->response = $this->ok($state);
    }
}
