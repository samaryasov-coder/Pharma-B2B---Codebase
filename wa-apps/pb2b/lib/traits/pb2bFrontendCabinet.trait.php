<?php
trait pb2bFrontendCabinetTrait {

    protected pb2bCabinetContext $context;
    private function initContext(): void
    {
        $this->context = pb2bCabinetContextFactory::build();
    }

    public function executeAction()
    {
        $role = $this->context->role();

        if ($role === pb2bCompanyRole::BUYER) {
            $this->executeBuyer();
        } elseif ($role === pb2bCompanyRole::SUPPLIER) {
            $this->executeSupplier();
        } else {
            throw new waException('Неизвестная роль');
        }
    }

    protected function executeBuyer()
    {
    }

    protected function executeSupplier()
    {
    }
}