<?php

class pb2bFrontendCabinetTenderApplicationAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        throw new waException('Страница только для поставщика', pb2bHttpStatus::FORBIDDEN);
    }

    public function executeSupplier()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0) {
            throw new waException('Тендер не найден', pb2bHttpStatus::BAD_REQUEST);
        }
        $this->view->assign(
            (new pb2bTenderSupplierApplicationPageService())->getSupplierPageData($tender_id, $company)
        );
        $this->setThemeTemplate('html/cabinet/supplier/tender-application.html');
    }
}
