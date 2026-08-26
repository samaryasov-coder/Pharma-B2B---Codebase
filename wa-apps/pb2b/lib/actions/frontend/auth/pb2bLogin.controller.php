<?php
class pb2bLoginController extends pb2bFrontendController
{
    public function executeAction()
    {
        $message = 'Ошибка авторизации';
        if (waRequest::issetPost(waAuth::LOGIN_FIELD_PHONE))
            $login_field = waAuth::LOGIN_FIELD_PHONE;
        elseif (waRequest::issetPost(waAuth::LOGIN_FIELD_EMAIL))
            $login_field = waAuth::LOGIN_FIELD_EMAIL;
        else
            return $this->setErrorResponse($message)
                ->withDetail('phone', 'Обязательное поле')
                ->withDetail('email', 'Обязательное поле');

        $login_value = waRequest::post($login_field, '', 'string');
        if (!trim($login_value))
            return $this->setErrorResponse($message)->withDetail($login_field,'Обязательное поле');

        $password = waRequest::post('password', '', 'string');
        if (!trim($password))
            return $this->setErrorResponse($message)->withDetail('password','Обязательное поле');

        $validator = ($login_field == waAuth::LOGIN_FIELD_PHONE) ? new pb2bPhoneNumberValidator(['required'=>true]) : new waEmailValidator(['required'=>true]);
        if (!$validator->isValid($login_value))
            return $this->setErrorResponse($message)->withDetail($login_field,'Некорректный формат');

        try {
            if (wa()->getAuth()->auth(['login' => $login_value, 'password' => $password]))
                $this->setSuccessResponse()->withMessage('Успешная авторизация');
        } catch (waAuthException $e){
            $this->setErrorResponse($message)
                ->withDetail($login_field, 'Неверное значение')
                ->withDetail('password', 'Неверное значение');
        }
    }
}
