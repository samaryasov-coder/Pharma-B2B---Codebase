<?php

class pb2bFrontendApiBuyerTenderApplicationDocumentFileController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        (new pb2bTenderApplicationService())->downloadDocumentFileForBuyer(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            waRequest::param('application_id', 0, waRequest::TYPE_INT),
            waRequest::param('document_id', 0, waRequest::TYPE_INT),
            $this->context->company()
        );
    }
}
