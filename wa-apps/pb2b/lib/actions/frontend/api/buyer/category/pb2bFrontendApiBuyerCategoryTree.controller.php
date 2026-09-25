<?php

class pb2bFrontendApiBuyerCategoryTreeController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $rows = (new pb2bCategoryCollection())->getCabinetTreeRows();

        $this->response = [
            'error' => false,
            'categories' => $rows,
            'classifier_type' => 4,
            'classifier_type_code' => 'category',
        ];
    }
}
