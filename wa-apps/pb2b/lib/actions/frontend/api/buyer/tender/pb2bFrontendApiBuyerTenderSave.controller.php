<?php

class pb2bFrontendApiBuyerTenderSaveController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $access = $this->assertBuyerAccess();
        if ($access !== null) {
            $this->response = $access;
            return;
        }

        $company = $this->context->company();
        $tender_id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $step = waRequest::post('step', '', waRequest::TYPE_STRING_TRIM);
        $data = waRequest::post('data', array(), waRequest::TYPE_ARRAY);

        if (waRequest::post('validate_step', 0, waRequest::TYPE_INT)) {
            $this->response = $company->tenderValidateStepFromBuyer($step, $data, $tender_id);
            return;
        }

        $this->response = $company->tenderSaveWizardFromBuyer($step, $data, $tender_id);
    }
}
