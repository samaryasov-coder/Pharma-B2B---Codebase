<?php

/**
 * GET api/buyer/tender/<id>/ranking/
 * Сравнение КП после вскрытия (цены открыты). До start-review — 409.
 */
class pb2bFrontendApiBuyerTenderRankingController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $ranking = $this->applicationService()->rankingForBuyer(
            $tender_id,
            $this->tenderCompanyId()
        );

        $this->response = $this->ok($ranking);
    }
}
