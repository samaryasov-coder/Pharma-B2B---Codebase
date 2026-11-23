<?php

class pb2bFrontendApiBuyerPrequalFileController extends pb2bFrontendCabinetController
{
    protected function executeBuyer(): void
    {
        (new pb2bPrequalService())->downloadDocumentFromBuyer(
            waRequest::param('id', 0, waRequest::TYPE_INT),
            waRequest::param('file_link_id', 0, waRequest::TYPE_INT),
            $this->context->company()
        );
        exit;
    }
}
