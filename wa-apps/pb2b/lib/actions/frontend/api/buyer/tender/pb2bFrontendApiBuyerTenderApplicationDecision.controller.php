<?php

/**
 * POST api/buyer/tender/<id>/applications/<application_id>/decision/
 * Тело: approval_status? / qualification_status? / admission_status? + comment?
 */
class pb2bFrontendApiBuyerTenderApplicationDecisionController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $application_id = waRequest::param('application_id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }
        if ($application_id <= 0) {
            throw new waException('Не указана заявка', pb2bHttpStatus::BAD_REQUEST);
        }

        $payload = array(
            'approval_status' => waRequest::post('approval_status', null, waRequest::TYPE_STRING_TRIM),
            'qualification_status' => waRequest::post('qualification_status', null, waRequest::TYPE_STRING_TRIM),
            'admission_status' => waRequest::post('admission_status', null, waRequest::TYPE_STRING_TRIM),
            'comment' => waRequest::post('comment', null, waRequest::TYPE_STRING_TRIM),
            'approval_comment' => waRequest::post('approval_comment', null, waRequest::TYPE_STRING_TRIM),
            'qualification_comment' => waRequest::post('qualification_comment', null, waRequest::TYPE_STRING_TRIM),
            'admission_comment' => waRequest::post('admission_comment', null, waRequest::TYPE_STRING_TRIM),
        );

        $result = $this->applicationService()->decideForBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            $application_id,
            $payload
        );

        $this->response = $this->ok(array(
            'tender' => $result['tender'] ?? null,
            'application' => $result['application'] ?? null,
            'prices_visible' => (int) ($result['prices_visible'] ?? 0),
        ), 'Решение сохранено');
    }
}
