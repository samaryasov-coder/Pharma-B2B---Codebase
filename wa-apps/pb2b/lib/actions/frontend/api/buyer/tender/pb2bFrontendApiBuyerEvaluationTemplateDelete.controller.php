<?php

class pb2bFrontendApiBuyerEvaluationTemplateDeleteController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        $this->response = $this->ok((new pb2bTenderEvaluationTemplateService())->delete(
            $this->context->contact(),
            $this->context->company(),
            waRequest::post('id', '', waRequest::TYPE_STRING_TRIM)
        ));
    }
}
