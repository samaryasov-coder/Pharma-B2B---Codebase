<?php

class pb2bFrontendCabinetTenderAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $overview = (new pb2bTenderService())->getBuyerOverview($tender_id, $company);
        $this->view->assign($overview);
        $this->view->assign(array(
            'tabs' => pb2bTenderPageTab::buyerTender(
                $overview['counts']['participants'],
                $overview['review_started'] || !empty($overview['tender']->data['winner_selection_completed'])
            ),
            'application_tabs' => pb2bTenderPageTab::buyerApplication(),
        ));
        $this->setCabinetThemeTemplate('tender.html');
    }

    public function executeSupplier()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $overview = (new pb2bTenderService())->getSupplierOverview($tender_id, $company);
        $this->view->assign($overview);
        $this->view->assign(array(
            'tabs' => pb2bTenderPageTab::supplierTender($overview['counts']['items'], $overview['counts']['documents']),
        ));
        $this->setCabinetThemeTemplate('tender.html');
    }
}
