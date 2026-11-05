<?php

class pb2bFrontendApiSupplierTenderCriterionFileController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiSupplierTenderTrait;

    public function executeSupplier(): void
    {
        $this->assertSupplierCompanySelected();
        (new pb2bTenderApplicationService())->downloadCriterionFileForSupplier(
            $this->requireTenderId(),
            waRequest::param('application_id', 0, waRequest::TYPE_INT),
            waRequest::param('criterion_id', 0, waRequest::TYPE_INT),
            $this->supplierCompanyId()
        );
    }
}
