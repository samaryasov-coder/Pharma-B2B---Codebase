<?php
class pb2bFrontendCabinetRedirectAction extends pb2bFrontendCabinetAction
{
    protected array $exceptMiddleware = [pb2bCompanyRoleMiddleware::class];

    public function executeAction(): void
    {
        if ($this->context->company()->isBuyer())
            $this->redirect('/cabinet/'.pb2bCompanyRole::BUYER->value.'/');
        elseif ($this->context->company()->isSupplier())
            $this->redirect('/cabinet/'.pb2bCompanyRole::SUPPLIER->value.'/');

        $this->redirect(wa()->getRootUrl());
    }
}