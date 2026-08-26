<?php
class pb2bRegistrationController extends pb2bFrontendController
{
    public function executeAction()
    {
        $message = 'Ошибка регистрации';
        $phone = waRequest::post('phone', '', 'string');

        if (!trim($phone))
            return $this->setErrorResponse($message)->withDetail('phone', 'Обязательное поле');

        if (!(new pb2bPhoneNumberValidator(['required'=>true]))->isValid($phone))
            return $this->setErrorResponse($message)->withDetail('phone', 'Некорректный формат');

        if ((new waContactDataModel())->getContactIdByPhone($phone) != false)
            return $this->setErrorResponse($message)->withDetail('phone', 'Телефон уже зарегистрирован в системе');

        $serviceCode = new pb2bAuthCodeService();
        $code_id = $serviceCode->send($phone,pb2bChannel::SMS,pb2bPurpose::REGISTER);
        if (empty($code_id))
            return $this->setErrorResponse($message)->withDetail('phone', $serviceCode->getLastError());

        $this->setSuccessResponse(['token' => $serviceCode->generateToken($code_id)])->withMessage('Код отправлен');
    }
}