<?php

class pb2bFrontendApiBuyerTenderCreateController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $payload = $this->tenderDtoPayloadFromRequest();
        $dto = new pb2bTenderDto([
            'type' => $payload['type'] ?? waRequest::post('type', 0, waRequest::TYPE_INT),
            'title' => $payload['title'] ?? waRequest::post('title', '', waRequest::TYPE_STRING_TRIM),
        ]);

        $tender = $this->tenderService()->createFromBuyer(
            $this->tenderCompanyId(),
            $dto,
            wa()->getUser()
        );

        $this->response = $this->tenderSuccessPayload($tender, 'Черновик создан');
    }
}
