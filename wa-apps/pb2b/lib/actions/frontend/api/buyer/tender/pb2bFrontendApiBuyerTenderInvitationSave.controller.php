<?php

class pb2bFrontendApiBuyerTenderInvitationSaveController extends pb2bFrontendCabinetController
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
        $supplier_ids = waRequest::post('invitations', array(), waRequest::TYPE_ARRAY);
        $this->response = $this->context->company()->tenderReplaceInvitationsFromBuyer($tender_id, $supplier_ids);
    }
}
