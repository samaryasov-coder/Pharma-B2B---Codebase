<?php

class pb2bFrontendApiBuyerTenderPublishController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $access = $this->assertBuyerAccess();
        if ($access !== null) {
            $this->response = $access;
            return;
        }

        $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $this->response = $this->context->company()->tenderPublishFromBuyer(
            $tender_id,
            waRequest::post('reason', null, waRequest::TYPE_STRING_TRIM),
            $this->context->contact()
        );
    }
}
