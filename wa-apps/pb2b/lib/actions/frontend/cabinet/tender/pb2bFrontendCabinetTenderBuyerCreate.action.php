<?php

class pb2bFrontendCabinetTenderBuyerCreateAction extends pb2bFrontendCabinetAction
{
    use pb2bTenderFormViewTrait;

    public function executeBuyer()
    {
        $type_code = trim((string) waRequest::get('type', '', waRequest::TYPE_STRING_TRIM));
        if ($type_code === '') $type_code = pb2bTenderType::PRICE_REQUEST->value;

        $type_row = pb2bTender::getTypeConfigByCode($type_code);
        if ($type_row === null) {
            $this->redirectToBuyerTendersList();
            return;
        }

        $steps = pb2bTender::getCreateSteps($type_code);
        if (!$steps) {
            $this->redirectToBuyerTendersList();
            return;
        }

        $this->assignTenderFormView(
            $type_code,
            (int) ($type_row['id'] ?? 0),
            0,
            false,
            $steps,
            pb2bTender::getCreateTitle($type_code, false)
        );
        $this->renderTenderFormTemplate();
    }
}
