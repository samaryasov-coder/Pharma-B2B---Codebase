<?php
abstract class pb2bResponse
{
    protected array $body;

    protected function __construct(array $body)
    {
        $this->body = $body;
    }

    public function withDetail(string $target, string $message, pb2bResponseType $type): static
    {
        $detail = ['type' => $type->value,];
        if ($message !== '')
            $detail['message'] = $message;

        $this->body['details'][$target] = $detail;

        return $this;
    }

    public function toArray(): array
    {
        return $this->body;
    }



    public static function success(?array $data = null): pb2bSuccessResponse
    {
        return new pb2bSuccessResponse($data);
    }

    public static function error(string $code, string $message): pb2bErrorResponse
    {
        return new pb2bErrorResponse($code, $message);
    }
}