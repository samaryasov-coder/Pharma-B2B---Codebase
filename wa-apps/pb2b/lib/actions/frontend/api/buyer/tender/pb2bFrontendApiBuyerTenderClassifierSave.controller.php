<?php

class pb2bFrontendApiBuyerTenderClassifierSaveController extends pb2bFrontendCabinetController
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
        $rows = waRequest::post('classifiers', array(), waRequest::TYPE_ARRAY);
        $this->response = $this->context->company()->tenderReplaceClassifiersFromBuyer($tender_id, $rows);
    }
}
