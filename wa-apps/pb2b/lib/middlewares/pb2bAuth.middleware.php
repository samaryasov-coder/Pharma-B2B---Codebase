<?php
class pb2bAuthMiddleware
{
    public function handle(waRequest $request, ...$auths)
    {
        if (empty($auths)) {
            $auths = ['default'];
        }

        foreach ($auths as $auth) {
            if ($this->checkAuth($auth)) {
                return;
            }
        }

        throw new pb2bMiddlewareException('Отсутствует авторизация', pb2bHttpStatus::FOUND, ['redirect' => '/auth/login/']);
    }

    protected function checkAuth(string $auth): bool
    {
        switch ($auth) {
            case 'api':
                return isset($_SESSION['api']);
            default:
                return wa()->getUser()->isAuth();
        }
    }
}