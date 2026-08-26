<?php
class pb2bFrontendController extends waJsonController
{
    use pb2bFrontendTrait;
    protected $response = null;

    protected function setSuccessResponse(array $data = []): pb2bSuccessResponse
    {
        return $this->response = pb2bResponse::success($data);
    }

    protected function setErrorResponse(string $message, string $code = ''): pb2bErrorResponse
    {
        return $this->response = pb2bResponse::error($code, $message);
    }

    public final function display(): void
    {
        $response = $this->response;// ?? pb2bResponse::error(500, 'internalError', 'Не удалось сформировать ответ');

        $this->getResponse()->addHeader('Content-Type', 'application/json');
        $this->getResponse()->sendHeaders();

        echo waUtils::jsonEncode($response->toArray());
    }



    protected function handleMiddlewareError(pb2bMiddlewareException $e): void
    {
        $this->handleException($e);
    }

    protected function handleException(Throwable $e): void
    {
        $this->getResponse()->setStatus($e->getCode());
        $this->error($e->getMessage());
    }
}