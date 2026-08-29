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

    public function __construct(array $data = [])
    {
        if (array_key_exists('invitations', $data)) {
            $invitations = $data['invitations'];
            $data['invitations'] = is_array($invitations)
                ? array_values(array_map('intval', $invitations))
                : [];
        }
        parent::__construct($data);
    }

    /** Поля для `$tender->save()` — без type и invitations. */
    public function toSaveArray(): array
    {
        $row = $this->toArray();
        unset($row['type'], $row['invitations']);

        return $row;
    }

    public function hasInvitations(): bool
    {
        return (new ReflectionProperty($this, 'invitations'))->isInitialized($this);
    }
}
