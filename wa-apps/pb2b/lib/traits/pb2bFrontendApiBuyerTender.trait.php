<?php

trait pb2bFrontendApiBuyerTenderTrait
{
    protected function assertBuyerCompanySelected(): void
    {
        $company = $this->context->company();
        if (!$company || !$company->id) {
            throw new waException('Компания не выбрана', pb2bHttpStatus::BAD_REQUEST);
        }
        if (!$company->isBuyer()) {
            throw new waException(
                'Создавать тендер может только компания-покупатель',
                pb2bHttpStatus::FORBIDDEN
            );
        }
    }

    protected function tenderService(): pb2bTenderService
    {
        return new pb2bTenderService();
    }

    protected function tenderCompanyId(): int
    {
        return (int) $this->context->company()->id;
    }

    /**
     * Поля тендера из POST: либо вложенный data[], либо плоские ключи (без id/step).
     */
    protected function tenderDtoPayloadFromRequest(): array
    {
        $nested = waRequest::post('data', null, waRequest::TYPE_ARRAY);
        if (is_array($nested) && $nested !== []) {
            return $nested;
        }

        $post = waRequest::post();
        if (!is_array($post)) {
            return [];
        }
        unset($post['id'], $post['step'], $post['_csrf']);

        return $post;
    }

    protected function tenderSuccessPayload(pb2bTender $tender, string $message): array
    {
        return [
            'error' => false,
            'message' => $message,
            'tender_id' => (int) $tender->id,
            'tender' => pb2bTenderResource::make($tender)->resolve(),
        ];
    }
}
