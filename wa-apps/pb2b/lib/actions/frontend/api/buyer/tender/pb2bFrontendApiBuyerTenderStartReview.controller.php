<?php

/**
 * POST api/buyer/tender/<id>/start-review/
 * После end_at: accepting_applications → opening_applications → review_and_admission.
 */
class pb2bFrontendApiBuyerTenderStartReviewController extends pb2bFrontendCabinetController
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
        $result = $this->tenderService()->startReviewForBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            $reason !== '' ? $reason : null,
            wa()->getUser()
        );

        $this->response = $this->ok($result, 'Рассмотрение заявок начато');
    }
}
