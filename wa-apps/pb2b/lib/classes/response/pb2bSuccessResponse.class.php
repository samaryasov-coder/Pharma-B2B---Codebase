<?php
final class pb2bSuccessResponse extends pb2bResponse
{
    private function setMessage(pb2bResponseType $type, string $text): static
    {
        $this->body['message'] = [
            'type' => $type->value,
            'text' => $text,
        ];

        return $this;
    }

    public function __construct(?array $data = null)
    {
        parent::__construct([
            'status' => 'ok',
        ]);

        if ($data !== null) {
            $this->body['data'] = $data;
        }
    }

    public function withMessage(string $text): static
    {
        return $this->setMessage(pb2bResponseType::SUCCESS, $text);
    }

    public function withInfoMessage(string $text): static
    {
        return $this->setMessage(pb2bResponseType::INFO, $text);
    }

    public function withWarningMessage(string $text): static
    {
        return $this->setMessage(pb2bResponseType::WARNING, $text);
    }

    public function withDetail(string $target, string $message = '', pb2bResponseType $type = pb2bResponseType::SUCCESS): static
    {
        return parent::withDetail($target, $message, $type);
    }
}