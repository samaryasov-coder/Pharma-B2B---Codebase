<?php

class pb2bFrontendApiBuyerTenderPublishController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $reason = waRequest::post('reason', null, waRequest::TYPE_STRING_TRIM);
        $tender = $this->tenderService()->publishFromBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            $reason !== '' ? $reason : null,
            wa()->getUser()
        );

        $this->response = $this->tenderSuccessPayload($tender, 'Опубликовано');
    }
}
