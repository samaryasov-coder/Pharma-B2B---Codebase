<?php

class pb2bFrontendApiBuyerTenderListController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $filters = [
            'status' => waRequest::get('status', 0, waRequest::TYPE_INT),
            'type' => waRequest::get('type', 0, waRequest::TYPE_INT),
        ];

        $this->response = [
            'error' => false,
            'items' => $this->tenderService()->listFromBuyer($this->tenderCompanyId(), $filters),
        ];
    }
}
