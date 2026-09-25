<?php

trait pb2bFrontendApiSupplierTenderTrait
{
    protected function assertSupplierCompanySelected(): void
    {
        $company = $this->context->company();
        if (!$company || !$company->id) {
            throw new waException('Компания не выбрана', pb2bHttpStatus::BAD_REQUEST);
        }
        if (!$company->isSupplier()) {
            throw new waException(
                'Участвовать в тендере может только компания-поставщик',
                pb2bHttpStatus::FORBIDDEN
            );
        }
    }

    protected function applicationService(): pb2bTenderApplicationService
    {
        return new pb2bTenderApplicationService();
    }

    protected function supplierCompanyId(): int
    {
        return (int) $this->context->company()->id;
    }

    protected function requestTenderId(): int
    {
        $id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($id <= 0) {
            $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        }

        return $id;
    }

    protected function requireTenderId(): int
    {
        $tender_id = $this->requestTenderId();
        if ($tender_id <= 0) {
            throw new waException('Не указан тендер', pb2bHttpStatus::BAD_REQUEST);
        }

        return $tender_id;
    }

    /**
     * @return array<string, mixed>
     */
    protected function applicationPayloadFromRequest(): array
    {
        $nested = waRequest::post('data', null, waRequest::TYPE_ARRAY);
        if (is_array($nested) && $nested !== []) {
            return $nested;
        }

        $post = waRequest::post();
        if (!is_array($post)) {
            return array();
        }
        unset($post['id'], $post['_csrf']);

        return $post;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    protected function ok(array $payload, string $message = ''): array
    {
        $out = array_merge(array('error' => false), $payload);
        if ($message !== '') {
            $out['message'] = $message;
        }

        return $out;
    }
}
