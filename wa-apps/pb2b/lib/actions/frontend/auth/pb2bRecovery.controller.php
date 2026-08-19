<?php
class pb2bRecoveryController extends pb2bFrontendController
{
    const LOGIN_FIELD_PHONE = 'phone';
    const LOGIN_FIELD_EMAIL = 'email';
    private function errorResponse($fields = []){
        return ['result' => 0, 'message' => 'Ошибка восстановления', 'fields' => $fields];
    }

    public function executeAction(){
        if (waRequest::issetPost(self::LOGIN_FIELD_PHONE))
            $login_field = self::LOGIN_FIELD_PHONE;
        elseif (waRequest::issetPost(self::LOGIN_FIELD_EMAIL))
            $login_field = self::LOGIN_FIELD_EMAIL;
        else
            return $this->response = $this->errorResponse(['phone' => 'Обязательное поле', 'email' => 'Обязательное поле']);

        $login_value = waRequest::post($login_field, '', 'string');
        if (!trim($login_value))
            return $this->response = $this->errorResponse([$login_field => 'Обязательное поле']);

        $validator = ($login_field == self::LOGIN_FIELD_PHONE) ? new pb2bPhoneNumberValidator(['required'=>true]) : new waEmailValidator(['required'=>true]);
        if (!$validator->isValid($login_value))
            return $this->response = $this->errorResponse([$login_field => 'Некорректный формат']);

        $contact = ($login_field == self::LOGIN_FIELD_PHONE) ? (new waContactDataModel())->getContactIdByPhone($login_value) : (new waContactEmailsModel())->getContactIdByEmail($login_value);
        if ($contact == false)
            return $this->response = $this->errorResponse([$login_field => 'Данные не найдены']);

        $serviceCode = new pb2bAuthCodeService();
        $channel = ($login_field == self::LOGIN_FIELD_PHONE) ? pb2bChannel::SMS : pb2bChannel::EMAIL;

        $code_id = $serviceCode->send($login_value, $channel,pb2bPurpose::RESET_PASSWORD);
        if (empty($code_id))
            return $this->response = $this->errorResponse([$login_field => $serviceCode->getLastError()]);

        $this->response = ['result' => 1, 'message' => 'Код отправлен', 'token' => $serviceCode->generateToken($code_id)];
    }
}