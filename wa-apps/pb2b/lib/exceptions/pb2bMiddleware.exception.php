<?php
class pb2bMiddlewareException extends \Exception
{
    public int $status;
    public array $data;

    public function __construct(string $message = '', int $status = 500, array $data = [])
    {
        parent::__construct($message);
        $this->status = $status;
        $this->data = $data;
    }
}
