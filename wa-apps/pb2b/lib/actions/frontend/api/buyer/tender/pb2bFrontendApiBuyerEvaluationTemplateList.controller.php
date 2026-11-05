<?php

class pb2bFrontendApiBuyerEvaluationTemplateListController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        $this->response = $this->ok((new pb2bTenderEvaluationTemplateService())->get(
            $this->context->contact(),
            $this->context->company()
        ));
    }
}
