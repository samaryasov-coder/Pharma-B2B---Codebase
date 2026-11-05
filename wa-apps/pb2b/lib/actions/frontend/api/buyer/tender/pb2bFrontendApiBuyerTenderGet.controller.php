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

        $detail = $this->tenderService()->getDetailFromBuyer($tender_id, $this->context->company());
        $detail['can_edit'] = (int) (pb2bTenderPolicy::update($detail['tender'], $this->context->company())
            && $detail['tender']->canEdit());
        $detail['tender'] = pb2bTenderResource::make($detail['tender'])->resolve();
        $detail['items'] = pb2bTenderItemResource::collection($detail['items']);
        $detail['documents'] = pb2bTenderDocumentResource::collection($detail['documents']);
        $detail['classifiers'] = pb2bTenderClassifierResource::collection($detail['classifiers']);
        $detail['invitations'] = pb2bTenderInvitationResource::collection($detail['invitations']);
        $this->response = array('error' => false) + $detail;
    }
}
