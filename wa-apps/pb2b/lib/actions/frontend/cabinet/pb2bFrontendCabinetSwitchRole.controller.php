<?php
class pb2bFrontendCabinetSwitchRoleController extends pb2bFrontendCabinetController
{
    protected array $exceptMiddleware = [pb2bCompanyRoleMiddleware::class];
    public function executeAction()
    {
        $role = waRequest::post('role', '', waRequest::TYPE_STRING);
        try {
            $roleEnum = pb2bCompanyRole::from($role);
            if ($roleEnum == pb2bCompanyRole::BUYER && $this->context->company()->isBuyer() || $roleEnum == pb2bCompanyRole::SUPPLIER && $this->context->company()->isSupplier()) {
                return $this->response = ['result' => 1, 'message' => 'Успешное изменение роли', 'role'=>$roleEnum->value];
            }

            return $this->response = ['result' => 0, 'message' => 'Роль не доступна'];
        } catch (\ValueError $e) {
            $this->response = ['result' => 0, 'message' => 'Неверная роль'];
        }
    }
}