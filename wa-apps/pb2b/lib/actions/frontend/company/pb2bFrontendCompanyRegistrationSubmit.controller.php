<?php
class pb2bFrontendCompanyRegistrationSubmitController extends pb2bFrontendController
{
    protected $middlewares = [pb2bAuthMiddleware::class];

    public function executeAction()
    {
        $company_info = waRequest::post('company', [], waRequest::TYPE_ARRAY);
        $client_info = waRequest::post('client', [], waRequest::TYPE_ARRAY);

        $service = new pb2bCompanyService();
        $service->createCompany(new pb2bCompanyDto($company_info), wa()->getUser()->getId());

//        $companyObject = new pb2bCompany();
//        $company_info['contact_id'] = wa()->getUser()->getId();
//        $this->response = $companyObject->save($company_info);
    }
}