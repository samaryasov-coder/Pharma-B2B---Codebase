<?php
enum pb2bOrganizationType: string
{
    case OOO = 'ooo';
    case ODO = 'odo';
    case AO = 'ao';
    case OAO = 'oao';
    case ZAO = 'zao';
    case PAO = 'pao';

    public function name(): string
    {
        return match ($this) {
            self::OOO => 'ООО',
            self::ODO => 'ОДО',
            self::AO => 'АО',
            self::OAO => 'ОАО',
            self::ZAO => 'ЗАО',
            self::PAO => 'ПАО',
        };
    }

    public function title(): string
    {
        return match ($this) {
            self::OOO => 'Общество с ограниченной ответственностью',
            self::ODO => 'Общество с дополнительной ответственностью',
            self::AO => 'Акционерное общество',
            self::OAO => 'Открытое акционерное общество',
            self::ZAO => 'Закрытое акционерное общество',
            self::PAO => 'Публичное акционерное общество',
        };
    }

    public function toArray(): array
    {
        return [
            'code' => $this->value,
            'title' => $this->title(),
            'name' => $this->name(),
        ];
    }
}
