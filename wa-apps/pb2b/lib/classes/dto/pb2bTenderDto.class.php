<?php

readonly class pb2bTenderDto extends pb2bBaseDto
{
    /** Только create; в toSaveArray не попадает. */
    public int $type;

    public string $title;
    public string $number;
    public int $is_private;
    public int $submission_form;
    public int $retendering_enabled;
    public int $itemized_enabled;
    public int $approval_required;
    public int $hide_initial_price;
    public int $hide_participants_count;
    public int $hide_participant_prices;
    public string $rank_prices_mode;
    public int $organizer_sees_names;
    public ?string $docs_end_at;
    public int $allow_analogues;
    public string $vat_mode;
    public int $auto_extend_no_offers;
    public int $auto_extend_on_change;
    public int $auto_extend_period_min;
    public int $min_step_enabled;
    public string $min_step_base;
    public string $min_step_type;
    public ?float $min_step_value;
    public int $only_price_reduction;
    public int $require_additional_docs;
    public string $additional_info;
    public string $additional_delivery_info;
    public int $past_prequal_tender_id;
    public ?string $start_at;
    public ?string $end_at;
    public ?string $opening_at;
    public ?float $budget;
    public string $currency;
    public string $payment_terms;
    public string $delivery_terms;

    /** @var list<int> */
    public array $invitations;

    /** @var list<array{name?:string,type?:string,description?:string,is_mandatory?:int|bool,weight?:float|null}> */
    public array $criteria;

    /** @var list<array<string,mixed>> */
    public array $items;

    /** @var list<array<string,mixed>> */
    public array $documents;

    public function __construct(array $data = [])
    {
        if (array_key_exists('invitations', $data)) {
            $invitations = $data['invitations'];
            $data['invitations'] = is_array($invitations)
                ? array_values(array_map('intval', $invitations))
                : [];
        }
        if (array_key_exists('criteria', $data)) {
            $data['criteria'] = self::normalizeCriteria($data['criteria']);
        }
        if (array_key_exists('items', $data)) {
            $data['items'] = self::normalizeItems($data['items']);
        }
        if (array_key_exists('documents', $data)) {
            $data['documents'] = self::normalizeDocuments($data['documents']);
        }
        parent::__construct($data);
    }

    /** Поля для `$tender->save()` — без type / invitations / criteria / items / documents. */
    public function toSaveArray(): array
    {
        $row = $this->toArray();
        unset($row['type'], $row['invitations'], $row['criteria'], $row['items'], $row['documents']);

        return $row;
    }

    public function hasInvitations(): bool
    {
        return (new ReflectionProperty($this, 'invitations'))->isInitialized($this);
    }

    public function hasCriteria(): bool
    {
        return (new ReflectionProperty($this, 'criteria'))->isInitialized($this);
    }

    public function hasItems(): bool
    {
        return (new ReflectionProperty($this, 'items'))->isInitialized($this);
    }

    public function hasDocuments(): bool
    {
        return (new ReflectionProperty($this, 'documents'))->isInitialized($this);
    }

    /**
     * @param mixed $raw
     * @return list<array{name:string,type:string,description:string,is_mandatory:int,weight:?float}>
     */
    private static function normalizeCriteria($raw): array
    {
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $type = trim((string) ($row['type'] ?? 'non_price'));
            if ($type === '') {
                $type = 'non_price';
            }
            $weight = null;
            if (array_key_exists('weight', $row) && $row['weight'] !== '' && $row['weight'] !== null) {
                $weight = (float) $row['weight'];
            }
            $out[] = [
                'name' => $name,
                'type' => $type,
                'description' => trim((string) ($row['description'] ?? '')),
                'is_mandatory' => !empty($row['is_mandatory']) || !empty($row['required']) ? 1 : 0,
                'weight' => $weight,
            ];
        }

        return $out;
    }

    /**
     * @param mixed $raw
     * @return list<array<string,mixed>>
     */
    private static function normalizeItems($raw): array
    {
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        $sort = 0;
        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $qty = (float) ($row['qty'] ?? $row['quantity'] ?? 0);
            if ($qty <= 0) {
                $qty = 1;
            }
            $file_link_id = (int) ($row['file_link_id'] ?? 0);
            $max_price = null;
            if (array_key_exists('max_price_no_vat', $row) && $row['max_price_no_vat'] !== '' && $row['max_price_no_vat'] !== null) {
                $max_price = (float) $row['max_price_no_vat'];
            } elseif (array_key_exists('maxPriceNoVat', $row) && $row['maxPriceNoVat'] !== '' && $row['maxPriceNoVat'] !== null) {
                $max_price = (float) $row['maxPriceNoVat'];
            }
            $out[] = [
                'name' => $name,
                'qty' => $qty,
                'unit' => trim((string) ($row['unit'] ?? '')),
                'max_price_no_vat' => $max_price,
                'vat_rate' => trim((string) ($row['vat_rate'] ?? $row['vatRate'] ?? '')),
                'delivery_place' => trim((string) ($row['delivery_place'] ?? $row['deliveryPlace'] ?? '')),
                'comment' => trim((string) ($row['comment'] ?? '')),
                'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                'sort' => array_key_exists('sort', $row) ? (int) $row['sort'] : $sort,
            ];
            $sort++;
        }

        return $out;
    }

    /**
     * @param mixed $raw
     * @return list<array<string,mixed>>
     */
    private static function normalizeDocuments($raw): array
    {
        if (!is_array($raw)) {
            return [];
        }
        $out = [];
        $sort = 0;
        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }
            $kind = trim((string) ($row['kind'] ?? ''));
            if (!pb2bTenderDocument::isAllowedKind($kind)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $file_link_id = (int) ($row['file_link_id'] ?? 0);
            $out[] = [
                'kind' => $kind,
                'name' => $name,
                'description' => trim((string) ($row['description'] ?? '')),
                'is_required' => !empty($row['is_required']) || !empty($row['isRequired']) ? 1 : 0,
                'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
                'sort' => array_key_exists('sort', $row) ? (int) $row['sort'] : $sort,
            ];
            $sort++;
        }

        return $out;
    }
}
