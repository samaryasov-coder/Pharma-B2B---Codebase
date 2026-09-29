<?php

/**
 * Шаги wizard участия поставщика (URL ?step=…).
 * Не путать со статусами заявки в config (tender_application_*).
 */
enum pb2bTenderParticipationStep: string
{
    case NON_PRICE = 'nonPrice';
    case APPROVAL = 'approval';
    case QUALIFICATION = 'qualification';
    case PROPOSAL = 'proposal';

    public function name(): string
    {
        return match ($this) {
            self::NON_PRICE => 'Неценовые критерии',
            self::APPROVAL => 'Одобрение покупателя',
            self::QUALIFICATION => 'Предквалификация',
            self::PROPOSAL => 'Подача предложения',
        };
    }

    public function hint(): string
    {
        return match ($this) {
            self::NON_PRICE => 'Заполните обязательные неценовые критерии и сохраните ответы.',
            self::APPROVAL => 'Ожидайте решения организатора или приложите документы, если требуется одобрение.',
            self::QUALIFICATION => 'Ожидайте прохождения предквалификации. Шаг доступен параллельно с одобрением.',
            self::PROPOSAL => 'Укажите цены по позициям, приложите документы и подайте заявку.',
        };
    }

    /** Порядок этапов в stepper (1-based index через index()). */
    public function index(): int
    {
        $i = array_search($this, self::ordered(), true);

        return $i === false ? 1 : $i + 1;
    }

    /**
     * @return list<self>
     */
    public static function ordered(): array
    {
        return array(
            self::NON_PRICE,
            self::APPROVAL,
            self::QUALIFICATION,
            self::PROPOSAL,
        );
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        $out = array();
        foreach (self::ordered() as $step) {
            $out[] = $step->value;
        }

        return $out;
    }

    public static function tryParse(string $code): ?self
    {
        return self::tryFrom(trim($code));
    }

    public function toArray(): array
    {
        return array(
            'code' => $this->value,
            'name' => $this->name(),
            'index' => $this->index(),
        );
    }
}
