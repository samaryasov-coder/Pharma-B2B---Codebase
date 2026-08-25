<?php
final class pb2bResponse
{
    private function __construct(private readonly int $status, private readonly array $body) {}

    public static function success(array $data = [], int $status = 200): self
    {
        return new self($status, ['data' => $data]);
    }

    public static function error(int $status, string $code, string $message): self
    {
        return new self($status, [
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ]);
    }

    public function withMessage(string $type, string $text): self
    {
        return $this->with([
            'message' => [
                'type' => $type,
                'text' => $text,
            ],
        ]);
    }

    public function withDetail(string $target, string $type, string $message = ''): self
    {
        $details = $this->body['details'] ?? [];

        $detail = [
            'target' => $target,
            'type' => $type,
        ];

        if ($message !== '') {
            $detail['message'] = $message;
        }

        $details[] = $detail;

        return $this->with([
            'details' => $details,
        ]);
    }

    private function with(array $data): self
    {
        return new self($this->status,
            [
                ...$this->body,
                ...$data,
            ]
        );
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function toArray(): array
    {
        return $this->body;
    }
}