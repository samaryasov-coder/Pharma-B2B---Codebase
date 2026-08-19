<?php
class pb2bFrontendAuthRecoveryAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        wa()->getResponse()->setTitle('Восстановление пароля');
        $this->setThemeTemplate('html/auth/layout.html');
        $this->view->assign('auth_path', 'html/auth/recovery.html');
    }
}