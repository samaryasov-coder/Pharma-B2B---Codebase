<?php
class pb2bFrontendCompanyRegistrationAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        $this->getResponse()->setTitle('Регистрация компании');
        $this->setThemeTemplate('company.registration.html');

        $company = (new pb2bCompanyModel())->getByContact(wa()->getUser()->getId());
        $this->view->assign([
            'company'=>$company,
        ]);

    }
}