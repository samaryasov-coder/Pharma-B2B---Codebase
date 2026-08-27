<?php
class pb2bFrontendCabinetTendersBuyerFormAction extends pb2bFrontendCabinetBuyerFormAction
{
    protected string $module = 'tenders';

    public function methodAction()
    {
        $this->view->assign('methods', pb2bTender::getCreateModalMethods());
    }
}
