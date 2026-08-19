<?php
class pb2bFrontendAuthCodeAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        wa()->getResponse()->setTitle('Код верификации');
        $this->setThemeTemplate('html/auth/layout.html');

        $token = waRequest::request('token','', 'string');;
        $service = new pb2bAuthCodeService();
        $code = $service->getByToken($token);

        if ($code) {
            if ($code['channel'] === pb2bChannel::EMAIL->value)
                $this->view->assign('channel', 'E-mail');
            elseif ($code['channel'] === pb2bChannel::SMS->value)
                $this->view->assign('channel', 'СМС');
        }

        $this->view->assign('auth_path', 'html/auth/code.html');
        $this->view->assign('rate', pb2bAuthCodeService::getRateSeconds());
    }
}