<?php
class pb2bFrontendAuthPasswordAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        wa()->getResponse()->setTitle('Создание пароля');
        $this->setThemeTemplate('html/auth/layout.html');
        $this->view->assign('auth_path', 'html/auth/password.html');
    }
}