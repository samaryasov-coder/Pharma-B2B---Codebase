<?php

class pb2bFrontendApiBuyerTenderInvitationSaveController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $ids = waRequest::post('invitations', [], waRequest::TYPE_ARRAY_INT);
        $result = $this->tenderService()->replaceInvitationsFromBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            is_array($ids) ? $ids : []
        );

        $this->response = [
            'error' => false,
            'message' => (string) ($result['message'] ?? 'Приглашения сохранены'),
            'tender_id' => (int) ($result['tender_id'] ?? $tender_id),
            'count' => (int) ($result['count'] ?? 0),
        ];
    }
}
