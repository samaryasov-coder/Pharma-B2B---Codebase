<?php
class pb2bResendController extends pb2bFrontendController
{
    public function executeAction(){
        $token = waRequest::post('token','', 'string');;

        $serviceCode = new pb2bAuthCodeService();
        $code_id = $serviceCode->resend($token);
        if (empty($code_id))
            return $this->response = ['result' => 0, 'message' => $serviceCode->getLastError()];

        $this->response = ['result' => 1, 'message' => 'Код отправлен', 'token' => $serviceCode->generateToken($code_id)];
    }
}