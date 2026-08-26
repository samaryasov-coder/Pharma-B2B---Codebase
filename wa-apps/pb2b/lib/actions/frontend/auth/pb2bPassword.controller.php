<?php
class pb2bPasswordController extends pb2bFrontendController
{
    private function errorResponse($fields = []){
        return ['result' => 0, 'message' => 'Ошибка создания пароля', 'fields' => $fields];
    }

    public function executeAction()
    {
        $message = 'Ошибка создания пароля';
        $password = waRequest::post('password','', 'string');;
        $password_confirmation = waRequest::post('password_confirmation', '', 'string');

        if (empty($password))
            return $this->setErrorResponse($message)->withDetail('password', 'Обязательное поле');

        if (empty($password_confirmation))
            return $this->setErrorResponse($message)->withDetail('password_confirmation', 'Обязательное поле');

        if ($password !== $password_confirmation)
            return $this->setErrorResponse($message)->withDetail('password_confirmation', 'Пароли не совпадают');

        $isValid = (bool) preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#\-?]).{8,}$/', $password);
        if (!$isValid)
            return $this->setErrorResponse($message)->withDetail('password', 'Пароль не соответствует правилам');

        $contact = new waContact(wa()->getUser()->getId());
        $contact->setPassword($password);
        $contact->save();

        $this->setSuccessResponse()->withMessage('Пароль установлен');
    }
}