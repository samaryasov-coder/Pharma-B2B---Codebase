<?php

class pb2bDocflowRequestCancelFromReviewerController extends waJsonController
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
        $this->response = $company->docflowRequestCancelFromReviewer(
            waRequest::post('request_id', null, waRequest::TYPE_INT),
            waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM),
        );
    }
}
