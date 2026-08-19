<?php
enum pb2bDocflowRequestStatus: string
{
    case WAITING_PROVIDER = 'waiting_provider';
    case WAITING_REVIEW = 'waiting_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';

    public function name(): string
    {
        return match ($this) {
            self::WAITING_PROVIDER => 'Ожидает документы',
            self::WAITING_REVIEW => 'Ожидает проверки',
            self::APPROVED => 'Одобрено',
            self::REJECTED => 'Отклонено',
            self::CANCELLED => 'Отменено',
            self::EXPIRED => 'Истёк срок',
        };
    }

    public function type(): string
    {
        return match ($this) {
            self::WAITING_PROVIDER, self::WAITING_REVIEW => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'error',
            self::CANCELLED => 'brand',
            self::EXPIRED => '',
        };
    }

    public function toArray(): array
    {
        return [
            'code' => $this->value,
            'name' => $this->name(),
            'type' => $this->type(),
        ];
    }
}
