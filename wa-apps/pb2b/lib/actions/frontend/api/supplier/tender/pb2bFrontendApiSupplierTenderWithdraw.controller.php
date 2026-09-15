<?php

class pb2bFrontendApiSupplierTenderWithdrawController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $this->response = $this->ok(
            $this->applicationService()->withdraw(
                $this->requireTenderId(),
                $this->supplierCompanyId()
            ),
            'Заявка отозвана'
        );
    }
}
