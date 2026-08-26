<?php
class pb2bResendController extends pb2bFrontendController
{
    public function executeAction(){
        $token = waRequest::post('token','', 'string');;

        $serviceCode = new pb2bAuthCodeService();
        $code_id = $serviceCode->resend($token);
        if (empty($code_id))
            return $this->setErrorResponse($serviceCode->getLastError());

        $this->setSuccessResponse(['token' => $serviceCode->generateToken($code_id)])->withMessage('Код отправлен');
    }
}