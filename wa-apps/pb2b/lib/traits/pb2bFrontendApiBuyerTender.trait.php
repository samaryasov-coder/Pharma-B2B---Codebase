<?php

trait pb2bFrontendApiBuyerTenderTrait
{
    protected function assertBuyerAccess(): ?array
    {
        $company = $this->context->company();
        if (!$company || !$company->id) {
            return array('error' => true, 'message' => 'Компания не выбрана');
        }
        return $company->tenderAssertBuyer();
    }

    protected function loadOrganizerTender(int $tender_id): array
    {
        $company = $this->context->company();
        if (!$company || !$company->id) {
            return array('error' => true, 'message' => 'Компания не выбрана');
        }
        $loaded = $company->tenderLoadOrganizer($tender_id);
        if (!empty($loaded['error'])) {
            return $loaded;
        }
        return array(
            'error' => false,
            'tender' => $loaded['tender'],
            'company' => $company,
        );
    }
}
