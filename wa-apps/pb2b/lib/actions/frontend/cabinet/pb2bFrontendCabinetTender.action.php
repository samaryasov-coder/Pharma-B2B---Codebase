<?php

class pb2bFrontendCabinetTenderAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0 || !$company || !$company->id) {
            throw new waException('Тендер не найден', pb2bHttpStatus::BAD_REQUEST);
        }

        $tender = (new pb2bTenderService())->getFromBuyer($tender_id, (int) $company->id);
        $extra = (new pb2bTenderCollection())->getWithClassifiers((int) $tender->id);

        $this->view->assign([
            'tender' => $tender,
            'card' => pb2bTenderResource::make($tender)->resolve(),
            'criteria' => (array) ($extra['criteria'] ?? []),
            'invitations' => (array) ($extra['invitations'] ?? []),
            'classifiers' => (array) ($extra['classifiers'] ?? []),
            'organizer' => $company,
        ]);
        $this->setThemeTemplate('html/cabinet/buyer/tender.html');
    }
}
