<?php
class pb2bLogoutController extends pb2bFrontendController
{
    public function executeAction()
    {
        wa()->getAuth()->clearAuth();
        wa()->getStorage()->close();

        $this->response = ['result' => 1, 'message' => 'Уcпешный выход из системы'];
    }
}
