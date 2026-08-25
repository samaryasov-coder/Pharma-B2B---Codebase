<?php
class pb2bFrontendController extends waJsonController
{
    use pb2bFrontendTrait;
    
    private pb2bResponseType $responseType = pb2bResponseType::SUCCESS;
    protected $response = null;

    protected function success(array $data = [], int $status = pb2bHttpStatus::OK): pb2bResponse
    {
        return $this->response = pb2bResponse::success($data, $status);
    }

    protected function error(int $http_status, string $code, string $message): pb2bResponse
    {
        return $this->response = pb2bResponse::error($http_status, $code, $message);
    }

    public final function display(): void
    {
        $response = $this->response ?? pb2bResponse::error(500, 'internalError', 'Не удалось сформировать ответ');

        $this->getResponse()->addHeader('Content-Type', 'application/json');
        $this->getResponse()->setStatus($response->getStatus());
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