<?php

class pb2bFrontendApiBuyerTenderCriterionFileController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();
        (new pb2bTenderApplicationService())->downloadCriterionFileForBuyer(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            waRequest::param('application_id', 0, waRequest::TYPE_INT),
            waRequest::param('criterion_id', 0, waRequest::TYPE_INT),
            $this->tenderCompanyId()
        );
    }
}
