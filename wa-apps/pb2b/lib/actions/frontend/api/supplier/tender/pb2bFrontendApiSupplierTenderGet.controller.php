<?php

class pb2bFrontendApiSupplierTenderGetController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $detail = $this->applicationService()->getNotice(
            $this->requireTenderId(),
            $this->supplierCompanyId()
        );
        $this->response = $this->ok($detail);
    }
}
