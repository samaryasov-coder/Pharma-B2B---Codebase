<?php

class pb2bFrontendApiBuyerTenderCreateController extends pb2bFrontendCabinetController
{
    use pb2bFrontendApiBuyerTenderTrait;

    public function executeBuyer(): void
    {
        $this->assertBuyerCompanySelected();

        $payload = $this->tenderDtoPayloadFromRequest();
        $dto_data = [
            'type' => $payload['type'] ?? waRequest::post('type', 0, waRequest::TYPE_INT),
            'title' => $payload['title'] ?? waRequest::post('title', '', waRequest::TYPE_STRING_TRIM),
        ];
        $number = array_key_exists('number', $payload)
            ? $payload['number']
            : waRequest::post('number', null, waRequest::TYPE_STRING_TRIM);
        if ($number !== null && $number !== '') {
            $dto_data['number'] = $number;
        }
        $dto = new pb2bTenderDto($dto_data);

        $tender = $this->tenderService()->createFromBuyer(
            $this->tenderCompanyId(),
            $dto,
            wa()->getUser()
        );

        $this->response = $this->tenderSuccessPayload($tender, 'Черновик создан');
    }
}
