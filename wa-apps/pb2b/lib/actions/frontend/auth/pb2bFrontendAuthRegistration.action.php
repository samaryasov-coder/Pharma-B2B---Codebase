<?php
class pb2bFrontendAuthRegistrationAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        wa()->getResponse()->setTitle('Регистрация');
        $this->setThemeTemplate('html/auth/layout.html');
        $this->view->assign('auth_path', 'html/auth/registration.html');
    }
}