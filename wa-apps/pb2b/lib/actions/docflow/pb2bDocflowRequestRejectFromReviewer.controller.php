<?php

class pb2bDocflowRequestRejectFromReviewerController extends waJsonController
{
    public function execute(): void
    {
        $docflowRequest = new pb2bDocflowRequest(waRequest::post('request_id', null, waRequest::TYPE_INT));
        if (!$docflowRequest->id) {
            $this->response = array(
                'error' => true,
                'message' => 'Процесс не найден',
            );
            return;
        }
        $this->response = $docflowRequest->rejectFromReviewer(
            waRequest::post('reviewer_id', null, waRequest::TYPE_INT),
            waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM),
            waRequest::post('item_reasons', array(), waRequest::TYPE_ARRAY)
        );
    }
}
