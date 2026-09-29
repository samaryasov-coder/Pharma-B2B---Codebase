<?php

/**
 * GET api/buyer/tender/<id>/applications/ — список заявок для вкладки «Участники».
 * Draft скрыт; цены до вскрытия не отдаются (Service/Resource).
 */
class pb2bFrontendApiBuyerTenderApplicationListController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        $payload = $this->applicationService()->getForBuyer($tender_id, $this->tenderCompanyId());
        $this->response = $this->ok(array(
            'tender' => $payload['tender'] ?? null,
            'applications' => (array) ($payload['applications'] ?? array()),
            'prices_visible' => (int) ($payload['prices_visible'] ?? 0),
        ));
    }
}
