<?php
trait pb2bFrontendTrait {

    protected array $exceptMiddleware = [];

    private function getClassHierarchy(string $class): array
    {
        return array_reverse(array_merge(
            [$class],
            class_parents($class)
        ));
    }

    private function matchPattern(string $pattern, string $uri): bool
    {
        $pattern = ltrim($pattern, '/');
        $uri = ltrim($uri, '/');
        $regex = '/^' . str_replace('\*', '.*', preg_quote($pattern, '/')) . '$/u';

        return (bool)preg_match($regex, $uri);
    }


    private function buildMiddlewareStack(): array
    {
        $config = require wa()->getAppPath('lib/config/middlewares.php');
        $stackMap = [];

        foreach ($config['global'] ?? [] as $mw) {
            $stackMap[$this->mwKey($mw)] = $mw;
        }

        $uri = parse_url(waRequest::server('REQUEST_URI'), PHP_URL_PATH);
        foreach ($config['routes'] ?? [] as $pattern => $mwList) {
            if ($this->matchPattern($pattern, $uri)) {
                foreach ($mwList as $mw) {
                    $stackMap[$this->mwKey($mw)] = $mw;
                }
            }
        }

        $classes = $this->getClassHierarchy(static::class);
        foreach ($classes as $class) {
            foreach ($config['controllers'][$class] ?? [] as $mw) {
                $stackMap[$this->mwKey($mw)] = $mw;
            }
            foreach ($config['actions'][$class] ?? [] as $mw) {
                $stackMap[$this->mwKey($mw)] = $mw;
            }
        }

        $stack = array_values($stackMap);
        $except = $this->exceptMiddleware ?? [];
        if ($except) {
            $stack = array_filter($stack, fn($mw) => !in_array($this->mwKey($mw), $except, true));
        }

        return array_values($stack);
    }

    private function mwKey($mw): mixed
    {
        return is_string($mw) ? $mw : $mw[0];
    }

    protected function executeMiddlwares()
    {
        $stack = $this->buildMiddlewareStack();

//        try {
            foreach ($stack as $mw) {
                if (is_string($mw)) {
                    $class = $mw;
                    $params = [];
                }
                else {
                    $class = $mw[0];
                    $params = $mw[1] ?? [];
                }

                $middleware = new $class();
                $middleware->handle($this->getRequest(), ...$params);
            }
//        } catch (pb2bMiddlewareException $e) {
//            $this->handleMiddlewareError($e);
//        }
    }

    protected function executeAction() {}

    protected function handleException(Throwable $e): void {}

    final public function execute()
    {
        try{
            $this->executeMiddlwares();
            $this->executeAction();
        } catch (Throwable $e){
            $this->handleException($e);
        }
    }




    protected function handleApplicationError(waException $e): void
    {
        $status = $e->getCode();
        $data = [];

        if ($this->expectsJson()) {
            $this->handleJsonError($status, $e->getMessage(), $data);
            return;
        }

        $this->handleHtmlError($status, $e->getMessage(), $data);
    }


    protected function handleUnexpectedError(Throwable $e): void
    {
        waLog::log((string) $e, 'pb2b-exceptions.log');

        if ($this->expectsJson()) {
            $this->handleJsonError(500, 'Внутренняя ошибка сервера');
            return;
        }

        $this->handleHtmlError(500, 'Внутренняя ошибка сервера');
    }

    protected function expectsJson(): bool
    {
        return $this instanceof waJsonController || waRequest::isXMLHttpRequest() || str_contains((string) waRequest::server('HTTP_ACCEPT'), 'application/json');
    }

    protected function handleJsonError(int $status, string $message, array $data = []): void
    {
        wa()->getResponse()->setStatus($status);
        wa()->getResponse()->addHeader(
            'Content-Type',
            'application/json'
        );

        echo json_encode(
            array_merge(
                [
                    'result' => 0,
                    'message' => $message,
                ],
                $data
            ),
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    protected function handleHtmlError(int $status, string $message, array $data = []): void
    {
        wa()->getResponse()->setStatus($status);

        if (!empty($data['redirect'])) {
            wa()->getResponse()->redirect($data['redirect'], $status);
            exit;
        }

        $this->renderError($status, $message);

        exit;
    }

    protected function renderError(int $status = 500, string $message = 'Внутренняя ошибка сервера'): void
    {
        wa()->getResponse()->setTitle($message);
        wa()->getResponse()->setStatus($status);

        $this->view->assign([
            'error_code' => $status,
            'error_message' => $message,
        ]);

        $this->setThemeTemplate('error.html');
    }
}