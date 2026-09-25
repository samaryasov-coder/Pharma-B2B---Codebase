<?php

class pb2bFrontendApiBuyerTenderGetController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $detail = $this->tenderService()->getDetailFromBuyer($tender_id, $this->tenderCompanyId());
        $this->response = array_merge(['error' => false], $detail);
    }
}
