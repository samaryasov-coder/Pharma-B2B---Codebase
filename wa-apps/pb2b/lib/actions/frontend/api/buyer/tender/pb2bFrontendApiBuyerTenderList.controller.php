<?php

class pb2bFrontendApiBuyerTenderListController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        $filters = array(
            'status' => waRequest::get('status', 0, waRequest::TYPE_INT),
            'type' => waRequest::get('type', 0, waRequest::TYPE_INT),
        );
        $tenders = (new pb2bTenderService())->listFromBuyer(
            $this->context->company(),
            $filters
        );

        $this->response = array(
            'error' => false,
            'items' => pb2bTenderListResource::collection($tenders),
        );
    }
}
