<?php
class pb2bGuestMiddleware {
    public function handle(waRequest $request, ...$guards)
    {
        if (empty($guards)) {
            $guards = ['default'];
        }

        foreach ($guards as $guard) {
            if ($this->checkAuth($guard)) {
                throw new pb2bMiddlewareException('Пользователь уже авторизирован', pb2bHttpStatus::FOUND, ['redirect' => '/cabinet']);
            }
        }
    }

    protected function checkAuth(string $guard): bool
    {
        switch ($guard) {
            case 'api':
                return isset($_SESSION['api']);
            default:
                return wa()->getUser()->isAuth();
        }
    }
}