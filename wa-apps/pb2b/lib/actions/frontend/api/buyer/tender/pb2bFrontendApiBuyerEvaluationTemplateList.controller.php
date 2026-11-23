<?php

class pb2bFrontendApiBuyerEvaluationTemplateListController extends pb2bFrontendCabinetController
{
    public function executeBuyer(): void
    {
        $this->response = array_merge(array('error' => false), (new pb2bTenderEvaluationTemplateService())->get(
            $this->context->contact(),
            $this->context->company()
        ));
    }
}
