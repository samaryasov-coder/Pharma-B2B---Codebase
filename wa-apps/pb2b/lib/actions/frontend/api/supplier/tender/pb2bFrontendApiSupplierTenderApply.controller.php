<?php

class pb2bFrontendApiSupplierTenderApplyController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $this->response = $this->ok(
            $this->applicationService()->getOrCreateDraft(
                $this->requireTenderId(),
                $this->supplierCompanyId()
            ),
            'Черновик заявки создан'
        );
    }
}
