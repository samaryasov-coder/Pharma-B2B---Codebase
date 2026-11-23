<?php

class pb2bFrontendApiSupplierTenderListController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $this->response = $this->ok(array(
            'items' => array_merge(
                $this->applicationService()->listForSupplier($this->supplierCompanyId()),
                (new pb2bPrequalService())->listFromSupplier($this->context->company())
            ),
        ));
    }
}
