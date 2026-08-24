<?php
enum pb2bDocflowRequestItemStatus: string
{
    case WAITING_PROVIDER = 'waiting_provider';
    case WAITING_REVIEW = 'waiting_review';
    case UPLOADED = 'uploaded';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public function name(): string
    {
        return match ($this) {
            self::WAITING_PROVIDER => 'Ожидает документ',
            self::WAITING_REVIEW => 'Ожидает проверки',
            self::UPLOADED => 'Документ загружен',
            self::ACCEPTED => 'Документ принят',
            self::REJECTED => 'Документ отклонён',
            self::CANCELLED => 'Отменено',
        };
    }

    public function type(): string
    {
        return match ($this) {
            self::WAITING_PROVIDER, self::WAITING_REVIEW => 'warning',
            self::UPLOADED, self::ACCEPTED => 'success',
            self::REJECTED => 'error',
            self::CANCELLED => 'brand',
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
