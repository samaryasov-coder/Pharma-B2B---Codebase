<?php
class pb2bRegistrationController extends pb2bFrontendController
{
    private function errorResponse($fields = []){
        return ['result' => 0, 'message' => 'Ошибка регистрации', 'fields' => $fields];
    }

    public function executeAction(){
        $phone = waRequest::post('phone', '', 'string');

        if (!trim($phone))
            return $this->response = $this->errorResponse(['phone' => 'Обязательное поле']);

        if (!(new pb2bPhoneNumberValidator(['required'=>true]))->isValid($phone))
            return $this->response = $this->errorResponse(['phone' => 'Некорректный формат']);

        if ((new waContactDataModel())->getContactIdByPhone($phone) != false)
            return $this->response = $this->errorResponse(['phone' => 'Телефон уже зарегистрирован в системе']);

        $serviceCode = new pb2bAuthCodeService();
        $code_id = $serviceCode->send($phone,pb2bChannel::SMS,pb2bPurpose::REGISTER);
        if (empty($code_id))
            return $this->response = $this->errorResponse(['phone' => $serviceCode->getLastError()]);

        $this->response = ['result' => 1, 'message' => 'Код отправлен', 'token' => $serviceCode->generateToken($code_id)];
    }
}