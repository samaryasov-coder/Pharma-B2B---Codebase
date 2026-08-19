<?php

class pb2bFrontendApiBuyerTenderGetController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $access = $this->assertBuyerAccess();
        if ($access !== null) {
            $this->response = $access;
            return;
        }

        $tender_id = (int) waRequest::param('id', 0, waRequest::TYPE_INT);
        $this->response = $this->context->company()->tenderGetWithClassifiers($tender_id);
    }
}
