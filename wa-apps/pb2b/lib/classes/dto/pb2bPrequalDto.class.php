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
    public ?string $delivery_address;
    public ?string $delivery_terms;
    public ?string $description;
    public array $invitations;
    public array $category_ids;
    public array $documents;
    public array $items;
}
