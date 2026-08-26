<?php
final class pb2bErrorResponse extends pb2bResponse
{
    public function __construct(string $code, string $message)
    {
        parent::__construct([
            'status' => 'fail',
            'code' => $code,
            'message' => $message,
        ]);
    }

    public function withDetail(string $target, string $message = '', pb2bResponseType $type = pb2bResponseType::ERROR): static
    {
        return parent::withDetail($target, $message, $type);
    }
}