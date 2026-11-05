<?php

readonly class pb2bPrequalDto extends pb2bBaseDto
{
    public string $title;
    public int $is_private;
    public ?int $validity_months;
    public ?string $accept_start_datetime;
    public ?string $accept_end_datetime;
    public ?string $results_planned_datatime;
    public int $requires_audit;
    public ?string $audit_info;
    public int $requires_samples;
    public ?string $samples_info;
    public ?string $additional_info;
    public ?string $delivery_terms;
    public ?string $description;
    public array $invitations;

    public function __construct(array $data = array())
    {
        foreach ($data as $field => &$value) {
            if (!property_exists(self::class, $field)) {
                throw new waException('Недопустимое поле ПК: '.$field, pb2bHttpStatus::BAD_REQUEST);
            }
            if ($field === 'invitations') {
                if ($value === '') {
                    $value = array();
                }
                if (!is_array($value)) {
                    throw new waException('Неверный список приглашённых компаний', pb2bHttpStatus::BAD_REQUEST);
                }
                $ids = array();
                foreach ($value as $supplier_id) {
                    if (!is_scalar($supplier_id) || is_bool($supplier_id)) {
                        throw new waException('Неверный ID поставщика', pb2bHttpStatus::BAD_REQUEST);
                    }
                    $id = filter_var($supplier_id, FILTER_VALIDATE_INT);
                    if ($id === false || $id <= 0) {
                        throw new waException('Неверный ID поставщика', pb2bHttpStatus::BAD_REQUEST);
                    }
                    $ids[$id] = $id;
                }
                $value = array_values($ids);
                continue;
            }
            if (!is_scalar($value) && $value !== null) {
                throw new waException('Неверное значение поля ПК: '.$field, pb2bHttpStatus::BAD_REQUEST);
            }
            if (in_array($field, array('is_private', 'requires_audit', 'requires_samples'), true)) {
                if (!in_array($value, array(0, 1, '0', '1', false, true), true)) {
                    throw new waException('Неверный флаг ПК: '.$field, pb2bHttpStatus::BAD_REQUEST);
                }
                $value = (int) $value;
            } elseif ($field === 'validity_months') {
                if ($value === null || $value === '') {
                    $value = null;
                } elseif (filter_var($value, FILTER_VALIDATE_INT) === false || (int) $value < 1) {
                    throw new waException('Срок квалификации должен быть положительным числом месяцев', pb2bHttpStatus::BAD_REQUEST);
                }
            } elseif (in_array($field, array('accept_start_datetime', 'accept_end_datetime', 'results_planned_datatime'), true)) {
                $raw = $value === null || trim((string) $value) === '' ? null : $value;
                list($ok, $normalized) = pb2bWaproHelper::normalizeDatetimeValueForMysql($raw);
                if (!$ok) {
                    throw new waException('Неверная дата ПК: '.$field, pb2bHttpStatus::BAD_REQUEST);
                }
                $value = $normalized;
            } elseif ($field === 'title') {
                if ($value === null) {
                    throw new waException('Наименование не может быть NULL', pb2bHttpStatus::BAD_REQUEST);
                }
                $value = trim((string) $value);
            } else {
                $value = $value === null ? null : trim((string) $value);
            }
        }
        unset($value);

        parent::__construct($data);
    }

    public function toSaveArray(): array
    {
        $data = $this->toArray();
        unset($data['invitations']);

        return $data;
    }
}
