<?php

/**
 * GET api/buyer/tender/<id>/applications/<application_id>/ — карточка заявки.
 * items/documents/criteria; price_per_unit только после вскрытия.
 */
class pb2bFrontendApiBuyerTenderApplicationGetController extends pb2bFrontendCabinetController
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

        $payload = $this->applicationService()->getForBuyer(
            $tender_id,
            $this->tenderCompanyId(),
            $application_id
        );
        $this->response = $this->ok(array(
            'tender' => $payload['tender'] ?? null,
            'application' => $payload['application'] ?? null,
            'prices_visible' => (int) ($payload['prices_visible'] ?? 0),
        ));
    }
}
