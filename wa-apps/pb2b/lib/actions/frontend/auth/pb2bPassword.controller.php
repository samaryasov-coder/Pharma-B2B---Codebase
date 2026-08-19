<?php
class pb2bPasswordController extends pb2bFrontendController
{
    private function errorResponse($fields = []){
        return ['result' => 0, 'message' => 'Ошибка создания пароля', 'fields' => $fields];
    }

    public function executeAction(){
        $password = waRequest::post('password','', 'string');;
        $password_confirmation = waRequest::post('password_confirmation', '', 'string');

        if (empty($password))
            return $this->response = $this->errorResponse(['password' => 'Обязательное поле']);

        if (empty($password_confirmation))
            return $this->response = $this->errorResponse(['password_confirmation' => 'Обязательное поле']);

        if ($password !== $password_confirmation)
            return $this->response = $this->errorResponse(['password_confirmation' => 'Пароли не совпадают']);

        $isValid = (bool) preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#\-?]).{8,}$/', $password);
        if (!$isValid)
            return $this->response = $this->errorResponse(['password' => 'Пароль не соответствует правилам']);

        $contact = new waContact(wa()->getUser()->getId());
        $contact->setPassword($password);
        $contact->save();

        $this->response = ['result' => 1, 'message' => 'Пароль установлен'];
    }
}