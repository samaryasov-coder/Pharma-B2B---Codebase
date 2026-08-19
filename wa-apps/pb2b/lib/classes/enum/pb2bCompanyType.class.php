<?php
enum pb2bCompanyType: string
{
    case ORGANIZATION = 'organization';
    case ENTREPRENEUR = 'entrepreneur';

    public function name(): string
    {
        return match ($this) {
            self::ORGANIZATION => 'Организация',
            self::ENTREPRENEUR => 'Предприниматель',
        };
    }

    public function shortName(): string
    {
        return match ($this) {
            self::ORGANIZATION => 'Организация',
            self::ENTREPRENEUR => 'ИП',
        };
    }

    public function toArray(): array
    {
        return [
            'code' => $this->value,
            'name' => $this->name(),
        ];
    }
}
