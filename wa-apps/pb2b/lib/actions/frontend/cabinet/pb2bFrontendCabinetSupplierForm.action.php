<?php
class pb2bFrontendCabinetSupplierFormAction extends pb2bFrontendCabinetFormAction
{
    public function executeAction()
    {
        $this->handle("supplier/$this->module/form");
    }
}