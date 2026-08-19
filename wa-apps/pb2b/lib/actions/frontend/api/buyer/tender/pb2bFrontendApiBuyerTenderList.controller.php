<?php

class pb2bFrontendApiBuyerTenderListController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $access = $this->assertBuyerAccess();
        if ($access !== null) {
            $this->response = $access;
            return;
        }

        $company = $this->context->company();
        $filters = array(
            'status' => waRequest::get('status', 0, waRequest::TYPE_INT),
            'type' => waRequest::get('type', 0, waRequest::TYPE_INT),
        );

        $collection = new pb2bTenderCollection();
        $this->response = array(
            'error' => false,
            'items' => $collection->getBuyerList((int) $company->id, $filters),
        );
    }
}
