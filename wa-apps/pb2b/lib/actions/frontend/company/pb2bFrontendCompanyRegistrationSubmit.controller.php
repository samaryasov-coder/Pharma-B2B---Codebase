<?php
class pb2bFrontendCompanyRegistrationSubmitController extends pb2bFrontendController
{
    protected $middlewares = [pb2bAuthMiddleware::class];

    public function executeAction()
    {
        $company_info = waRequest::post('company', [], waRequest::TYPE_ARRAY);

        $service = new pb2bCompanyService();
        $company = $service->createCompany(new pb2bCompanyDto($company_info), wa()->getUser()->getId());
        $this->response = ['error' => false, 'company_id' => $company->id];
    }
}
