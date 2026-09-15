<?php

class pb2bFrontendApiSupplierTenderSaveController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();

        $this->response = $this->ok(
            $this->applicationService()->save(
                $this->requireTenderId(),
                $this->supplierCompanyId(),
                $this->applicationPayloadFromRequest()
            ),
            'Сохранено'
        );
    }
}
