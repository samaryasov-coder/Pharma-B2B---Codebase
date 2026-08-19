<?php
class pb2bFrontendAuthLoginAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        wa()->getResponse()->setTitle('Авторизация');
        $this->setThemeTemplate('html/auth/layout.html');
        $this->view->assign('auth_path', 'html/auth/login.html');
    }
}