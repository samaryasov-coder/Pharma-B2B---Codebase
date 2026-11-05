<?php

class pb2bTenderResource extends pb2bBaseJsonResource
{
    private bool $supplier_view = false;

    public function forSupplier(): static
    {
        $this->supplier_view = true;

        return $this;
    }

    protected array $casts = array(
        'int' => array('id', 'tender_id'),
        'string' => array(
            'number',
            'title',
            'create_datetime',
            'update_datetime',
            'start_at',
            'end_at',
            'opening_at',
            'docs_end_at',
            'published_at',
        ),
    );

    public function toArray(): array
    {
        $tender = $this->resource;
        $row = $tender->data;

        $typeId = (int) ($row['type'] ?? 0);
        $statusId = (int) ($row['status'] ?? 0);
        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'id');
        $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
        $typeRow = $types[$typeId] ?? array();
        $statusRow = $statuses[$statusId] ?? array();

        $modes = (array) pb2bWaproHelper::getConfigOption('tender_winner_selection_modes', 'id');
        $modeId = (int) ($row['winner_selection_mode'] ?? 0);
        $modeRow = $modes[$modeId]
            ?? $modes[pb2bTender::winnerSelectionModeCodeToId('none')]
            ?? array();
        $id = (int) $tender->id;

        $result = array(
            'id' => $id,
            'tender_id' => $id,
            'number' => (string) ($row['number'] ?? ''),
            'title' => (string) ($row['title'] ?? ''),

            'type' => array(
                'id' => $typeId,
                'code' => (string) ($typeRow['code'] ?? ''),
                'name' => (string) ($typeRow['name'] ?? ''),
            ),

            'status' => array(
                'id' => $statusId,
                'code' => (string) ($statusRow['code'] ?? ''),
                'name' => (string) ($statusRow['name'] ?? ''),
                'tone' => (string) ($statusRow['tone'] ?? 'info'),
            ),

            'is_private' => (int) ($row['is_private'] ?? 0),
            'approval_required' => (int) ($row['approval_required'] ?? 0),
            'past_prequal_tender_id' => (int) ($row['past_prequal_tender_id'] ?? 0),
            'retendering_enabled' => (int) ($row['retendering_enabled'] ?? 0),
            'itemized_enabled' => (int) ($row['itemized_enabled'] ?? 0),
            'winner_selection_mode' => array(
                'id' => (int) ($modeRow['id'] ?? 0),
                'code' => (string) ($modeRow['code'] ?? 'none'),
                'name' => (string) ($modeRow['name'] ?? ''),
            ),
            'winner_selection_completed' => !empty($row['winner_selection_completed']) ? 1 : 0,
            'finished_without_winner' => !empty($row['finished_without_winner']) ? 1 : 0,
            'finished_without_winner_reason' => trim((string) ($row['finished_without_winner_reason'] ?? '')),
            'hide_initial_price' => (int) ($row['hide_initial_price'] ?? 0),
            'hide_participants_count' => (int) ($row['hide_participants_count'] ?? 0),
            'hide_participant_prices' => (int) ($row['hide_participant_prices'] ?? 0),
            'rank_prices_mode' => (string) ($row['rank_prices_mode'] ?? ''),
            'organizer_sees_names' => (int) ($row['organizer_sees_names'] ?? 0),
            'allow_analogues' => (int) ($row['allow_analogues'] ?? 0),
            'vat_mode' => (string) ($row['vat_mode'] ?? ''),
            'auto_extend_no_offers' => (int) ($row['auto_extend_no_offers'] ?? 0),
            'auto_extend_on_change' => (int) ($row['auto_extend_on_change'] ?? 0),
            'auto_extend_period_min' => (int) ($row['auto_extend_period_min'] ?? 0),
            'min_step_enabled' => (int) ($row['min_step_enabled'] ?? 0),
            'min_step_base' => (string) ($row['min_step_base'] ?? ''),
            'min_step_type' => (string) ($row['min_step_type'] ?? ''),
            'min_step_value' => $row['min_step_value'] ?? null,
            'only_price_reduction' => (int) ($row['only_price_reduction'] ?? 0),
            'require_additional_docs' => (int) ($row['require_additional_docs'] ?? 0),
            'additional_info' => (string) ($row['additional_info'] ?? ''),
            'additional_delivery_info' => (string) ($row['additional_delivery_info'] ?? ''),
            'start_at' => $row['start_at'] ?? null,
            'end_at' => $row['end_at'] ?? null,
            'opening_at' => $row['opening_at'] ?? null,
            'docs_end_at' => $row['docs_end_at'] ?? null,
            'published_at' => $row['published_at'] ?? null,
            'budget' => ($row['budget'] ?? null) === '' ? null : ($row['budget'] ?? null),
            'currency' => (string) ($row['currency'] ?? ''),
            'payment_terms' => (string) ($row['payment_terms'] ?? ''),
            'delivery_terms' => (string) ($row['delivery_terms'] ?? ''),
            'create_datetime' => $row['create_datetime'] ?? null,
            'update_datetime' => $row['update_datetime'] ?? null,
            'review' => array(
                'can_start' => $tender->canStartReview(),
                'is_started' => $tender->isReviewStarted(),
            ),
        );
        if ($this->supplier_view && !empty($row['hide_initial_price'])) {
            unset($result['budget']);
        }

        return $result;
    }
}
