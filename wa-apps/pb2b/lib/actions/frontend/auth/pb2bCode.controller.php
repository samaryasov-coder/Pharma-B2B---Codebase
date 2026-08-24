<?php
class pb2bCodeController extends pb2bFrontendController
{
    private function errorResponse($fields = []){
        return ['result' => 0, 'message' => 'Ошибка регистрации', 'fields' => $fields];
    }

    public function executeAction(){
        $token = waRequest::post('token','', 'string');;
        $code = waRequest::post('code', '', 'string');

        if (!trim($code))
            return $this->response = $this->errorResponse(['code' => 'Обязательное поле']);

        $serviceCode = new pb2bAuthCodeService();
        $is_verify = $serviceCode->verifyByToken($token, $code);
        if (!$is_verify)
            return $this->response = $this->errorResponse(['code' => $serviceCode->getLastError()]);

        $code = $serviceCode->getByToken($token);
        $identifier = [];
        $contact_id = null;
        $user_identifier = $code['user_identifier'];
        $channel = $code['channel'];

        $contactDataModel = new waContactDataModel();
        $contactEmailsModel = new waContactEmailsModel();

        switch ($channel){
            case pb2bChannel::SMS->value:
                $identifier = ['phone' => $user_identifier];
                $contact_id = $contactDataModel->getContactIdByPhone($user_identifier);
                break;
            case pb2bChannel::EMAIL->value:
                $identifier = ['email' => $user_identifier];
                $contact_id = $contactEmailsModel->getContactIdByEmail($user_identifier);
            break;
        }

        $contact = new waContact($contact_id);

        if (!$contact->getId()) {
            $contact->save([
                'name' => 'Пользователь',
                'login' => 'user_' . bin2hex(random_bytes(16)),
                'password' => waContact::generatePassword(),
                ...$identifier
            ]);
            switch ($channel) {
                case pb2bChannel::SMS->value:
                    $contactDataModel->updateContactPhoneStatus($contact->getId(), $user_identifier, $contactDataModel::STATUS_CONFIRMED);
                    break;
                case pb2bChannel::EMAIL->value:
                    $contactEmailsModel->updateContactEmailStatus($contact->getId(), $user_identifier, $contactEmailsModel::STATUS_CONFIRMED);
                    break;
            }
        }

        try {
            if (wa()->getAuth()->auth(['id' => $contact->getId()]))
                return $this->response = ['result' => 1, 'message' => 'Успешная авторизация'];
        } catch (waAuthException $e){}

        $this->response = ['result' => 0, 'message' => 'Ошибка авторизации'];
    }
}