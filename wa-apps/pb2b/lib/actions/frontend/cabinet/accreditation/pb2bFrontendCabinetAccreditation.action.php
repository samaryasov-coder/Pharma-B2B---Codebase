<?php
class pb2bFrontendCabinetAccreditationAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        $this->setThemeTemplate('html/cabinet/buyer/accreditation.html');
    }

    public function executeSupplier()
    {
        $this->setThemeTemplate('html/cabinet/supplier/accreditation.html');
    }
}