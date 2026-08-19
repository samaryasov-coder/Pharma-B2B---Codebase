<?php

class pb2bDocflowRequestCreateFromReviewerController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('reviewer_id', null, waRequest::TYPE_INT));
        if (!$company->id) {
            $this->response = array(
                'error' => true,
                'message' => 'Компания покупателя не найдена',
            );
            return;
        }
        $this->response = $company->docflowRequestCreateFromReviewer(array(
            'template_id' => waRequest::post('template_id', null, waRequest::TYPE_INT),
            'provider_id' => waRequest::post('provider_id', null, waRequest::TYPE_INT),
            'template_item_ids' => waRequest::post('template_item_ids', array(), waRequest::TYPE_ARRAY),
            'comment' => waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM),
        ));
    }
}
