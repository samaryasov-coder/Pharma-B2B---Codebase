<?php

class pb2bFrontendApiBuyerEvaluationTemplateSelectController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        $this->response = array_merge(array('error' => false), (new pb2bTenderEvaluationTemplateService())->select(
            $this->context->contact(),
            $this->context->company(),
            waRequest::post('id', '', waRequest::TYPE_STRING_TRIM)
        ));
    }
}
