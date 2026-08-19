<?php
class pb2bCompanyVerifiedMiddleware
{
    public function handle(waRequest $request, ...$auths)
    {
        if (!$this->companyVerified())
            throw new pb2bMiddlewareException('Компания не активна', pb2bHttpStatus::FOUND, ['redirect' => '/company-registration/']);
    }

    protected function companyVerified(): bool
    {
        $company_model = new pb2bCompanyModel();
        $company = $company_model->getByContact(wa()->getUser()->getId());
        if ($company && $company['data']['status']){
            return true;
        }

        return false;
    }
}