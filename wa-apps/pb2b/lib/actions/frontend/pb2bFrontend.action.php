<?php
class pb2bFrontendAction extends waViewAction
{
    use pb2bFrontendTrait;

    private ?bool $hx = null;

    protected final function isHxRequest()
    {
        if ($this->hx === null) {
            $this->hx = waRequest::server('HTTP_HX_REQUEST', false);
        }
        return $this->hx;
    }


    protected function handleException(Throwable $e): void
    {
        if ($e instanceof pb2bMiddlewareException) $this->handleMiddlewareException($e);
    }

    protected function handleMiddlewareException(pb2bMiddlewareException $e)
    {
        $redirect = ifempty($e->data['redirect'], '/error/');
        if ($this->isHxRequest()) {
            wa()->getResponse()->addHeader('HX-Redirect', $redirect);
            wa()->getResponse()->setStatus($e->status);
            return;
        }
        wa()->getResponse()->redirect($redirect, $e->status);
    }

    protected function renderError(int $status = 404, string $message = 'Страница не найдена')
    {
        wa()->getResponse()->setTitle($message);
        wa()->getResponse()->setStatus($status);
        $this->view->assign(['error_code' => $status, 'error_message' => $message]);
        $this->setThemeTemplate('error.html');
    }

    public function __construct()
    {
        parent::__construct();

        if ($this->isHxRequest() || waRequest::isXMLHttpRequest()){
            $this->setLayout();
        }
        else if (!$this->layout){
            $this->setLayout(new pb2bFrontendLayout());
        }
    }
}