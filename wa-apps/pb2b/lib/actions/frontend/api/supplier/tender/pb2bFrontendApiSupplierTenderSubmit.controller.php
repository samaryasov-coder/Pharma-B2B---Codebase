<?php

class pb2bFrontendApiSupplierTenderSubmitController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $this->response = $this->ok(
            $this->applicationService()->submit(
                $this->requireTenderId(),
                $this->supplierCompanyId()
            ),
            'Заявка подана'
        );
    }
}
