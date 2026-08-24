<?php

class pb2bFrontendApiBuyerTenderCreateController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $access = $this->assertBuyerAccess();
        if ($access !== null) {
            $this->response = $access;
            return;
        }

        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'code');
        $this->response = array(
            'error' => false,
            'tender_id' => 0,
            'status' => (int) ($statuses['draft']['id'] ?? 1),
            'message' => 'Создайте тендер через save с шагом basic',
        );
    }
}
