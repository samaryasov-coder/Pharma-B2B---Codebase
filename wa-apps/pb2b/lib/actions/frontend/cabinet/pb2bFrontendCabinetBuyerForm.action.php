<?php
class pb2bFrontendCabinetBuyerFormAction extends pb2bFrontendCabinetFormAction
{
    public function executeAction()
    {
        $this->handle("buyer/$this->module/form");
    }
}