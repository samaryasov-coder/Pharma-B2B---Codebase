<?php

class pb2bFrontendApiBuyerTenderSaveController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $dto = new pb2bTenderDto($this->tenderDtoPayloadFromRequest());
        $tender = $this->tenderService()->updateFromBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            $dto
        );

        $this->response = $this->tenderSuccessPayload($tender, 'Сохранено');
    }
}
