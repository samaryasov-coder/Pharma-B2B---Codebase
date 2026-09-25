<?php

class pb2bFrontendApiSupplierTenderListController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $this->response = $this->ok(array(
            'items' => $this->applicationService()->listForSupplier($this->supplierCompanyId()),
        ));
    }
}
