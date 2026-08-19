<?php
class pb2bDocflowRequestStatusReference extends pb2bBaseReference
{
    public const WAITING_PROVIDER = 1;
    public const WAITING_REVIEW   = 2;
    public const APPROVED = 3;
    public const REJECTED = 4;
    public const CANCELLED = 5;
    public const EXPIRED = 6;

    protected static function configField(): string
    {
        return 'docflow_request_statuses';
    }
}