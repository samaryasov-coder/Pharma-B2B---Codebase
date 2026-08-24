<?php
class pb2bCompanyRoleMiddleware
{
    protected function roleMatches(pb2bCompanyRole $role): bool
    {
        $company = (new pb2bCompanyModel())->getByContact(wa()->getUser()->getId());

        if (!$company) {
            return false;
        }

        return match ($role) {
            pb2bCompanyRole::BUYER => $company->isBuyer(),
            pb2bCompanyRole::SUPPLIER => $company->isSupplier(),
            default => false
        };
    }

    public function handle(waRequest $request, ...$auths)
    {
        $role = pb2bCabinetContextFactory::build()->role();
        if (!$role || !$this->roleMatches($role)) {
            throw new pb2bMiddlewareException('Нет прав для текущей роли', pb2bHttpStatus::FORBIDDEN);
        }
    }
}