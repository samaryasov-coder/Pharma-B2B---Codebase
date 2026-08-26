<?php
class pb2bLogoutController extends pb2bFrontendController
{
    public function executeAction()
    {
        wa()->getAuth()->clearAuth();
        wa()->getStorage()->close();

        $this->setSuccessResponse()->withMessage('Уcпешный выход из системы');
    }
}
