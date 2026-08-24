<?php
class pb2bFrontendController extends waJsonController
{
    use pb2bFrontendTrait;
    
    private pb2bResponseType $responseType = pb2bResponseType::SUCCESS;
    private string $responseMessage = '';

    private function buildResponse(): array
    {
        return [
            'status' => $this->responseType->value,
            'message' => $this->responseMessage,
            'payload' => $this->response
        ];
    }

    private function setResponse(pb2bResponseType $type, string $message = '', array $payload = []): array
    {
        $this->responseType = $type;
        $this->responseMessage = $message;
        $this->response = $payload;

        return $this->buildResponse();
    }

    protected function success(string $message = '', array $payload = []): array
    {
        return $this->setResponse(pb2bResponseType::SUCCESS, $message, $payload);
    }

    protected function error(string $message = '', array $payload = []): array
    {
        return $this->setResponse(pb2bResponseType::ERROR, $message, $payload);
    }

    public final function display(): void
    {
        if (waRequest::isXMLHttpRequest()) {
            $this->getResponse()->addHeader('Content-Type', 'application/json');
        }
        $this->getResponse()->sendHeaders();


        echo waUtils::jsonEncode($this->buildResponse());
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