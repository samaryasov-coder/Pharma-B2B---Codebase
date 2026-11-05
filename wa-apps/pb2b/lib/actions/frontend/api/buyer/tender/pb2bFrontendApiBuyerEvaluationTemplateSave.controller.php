<?php

class pb2bFrontendApiBuyerEvaluationTemplateSaveController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        $this->response = $this->ok((new pb2bTenderEvaluationTemplateService())->save(
            $this->context->contact(),
            $this->context->company(),
            waRequest::post('id', '', waRequest::TYPE_STRING_TRIM),
            waRequest::post('name', '', waRequest::TYPE_STRING_TRIM),
            waRequest::post('settings', array(), waRequest::TYPE_ARRAY)
        ));
    }
}
