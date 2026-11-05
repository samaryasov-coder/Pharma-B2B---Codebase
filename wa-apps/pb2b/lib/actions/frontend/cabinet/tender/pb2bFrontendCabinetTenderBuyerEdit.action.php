<?php

class pb2bFrontendCabinetTenderBuyerEditAction extends pb2bFrontendCabinetAction
{
    use pb2bTenderFormViewTrait;

    public function executeBuyer()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            $this->redirectToBuyerTendersList();
            return;
        }

        try {
            $detail = (new pb2bTenderService())->getDetailFromBuyer($tender_id, $company);
        } catch (waException $e) {
            $this->redirectToBuyerTendersList();
            return;
        }

        if (!pb2bTenderPolicy::update($detail['tender'], $company) || !$detail['tender']->canEdit()) {
            $this->redirectToBuyerTendersList();
            return;
        }

        $type_id = (int) $detail['tender']->data['type'];
        $type_code = pb2bTenderType::tryFromId($type_id)?->value ?? '';

        $this->assignTenderFormView(
            $type_code,
            $type_id,
            $tender_id,
            true,
            pb2bTender::getCreateSteps($type_code),
            pb2bTender::getCreateTitle($type_code, true),
            $detail
        );
        $this->renderTenderFormTemplate();
    }
}
