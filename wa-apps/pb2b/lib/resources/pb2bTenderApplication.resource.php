<?php

class pb2bTenderApplicationResource extends pb2bBaseJsonResource
{
    private bool $include_prices = false;

    /** @var list<object|array> */
    private array $items = array();

    /** @var list<object|array> */
    private array $documents = array();

    /** @var list<object|array> */
    private array $criteria = array();

    protected array $casts = [
        'int' => ['id', 'tender_id', 'supplier_company_id', 'nonprice_done'],
        'string' => [
            'submitted_at',
            'withdrawn_at',
            'create_datetime',
            'update_datetime',
        ],
    ];

    public function withPrices(bool $include = true): static
    {
        $this->include_prices = $include;

        return $this;
    }

    /**
     * @param list<object|array> $items
     */
    public function withItems(array $items): static
    {
        $this->items = array_values($items);

        return $this;
    }

    /**
     * @param list<object|array> $documents
     */
    public function withDocuments(array $documents): static
    {
        $this->documents = array_values($documents);

        return $this;
    }

    /**
     * @param list<object|array> $criteria
     */
    public function withCriteria(array $criteria): static
    {
        $this->criteria = array_values($criteria);

        return $this;
    }

    /**
     * Организатор видит цены только после вскрытия.
     */
    public static function isBuyerPriceVisible(string $tender_status_code): bool
    {
        return in_array($tender_status_code, array(
            'vskrytie_zayavok',
            'rassmotrenie_i_dopusk',
            'peretorzhka_aktivna',
            'auktsionnye_torgi',
            'otsenka',
            'podvedenie_itogov',
            'zaklyuchenie_dogovora',
            'arkhiv',
        ), true);
    }

    public function toArray(): array
    {
        $row = pb2bTenderApplicationItemResource::waproRow($this->resource);
        $status_code = trim((string) ($row['status'] ?? ''));
        $approval_code = trim((string) ($row['approval_status'] ?? ''));
        $qualification_code = trim((string) ($row['qualification_status'] ?? ''));
        $admission_code = trim((string) ($row['admission_status'] ?? ''));

        return array(
            'id' => (int) ($row['id'] ?? 0),
            'tender_id' => (int) ($row['tender_id'] ?? 0),
            'supplier_company_id' => (int) ($row['supplier_company_id'] ?? 0),
            'status' => self::codePayload('tender_application_statuses', $status_code),
            'nonprice_done' => !empty($row['nonprice_done']) ? 1 : 0,
            'approval_status' => self::codePayload('tender_application_approval_statuses', $approval_code),
            'qualification_status' => self::codePayload('tender_application_qualification_statuses', $qualification_code),
            'admission_status' => self::codePayload('tender_application_admission_statuses', $admission_code),
            'approval_comment' => $row['approval_comment'] ?? null,
            'qualification_comment' => $row['qualification_comment'] ?? null,
            'admission_comment' => $row['admission_comment'] ?? null,
            'submitted_at' => $row['submitted_at'] ?? null,
            'withdrawn_at' => $row['withdrawn_at'] ?? null,
            'create_datetime' => $row['create_datetime'] ?? null,
            'update_datetime' => $row['update_datetime'] ?? null,
            'prices_visible' => $this->include_prices ? 1 : 0,
            'items' => $this->mapItems(),
            'documents' => pb2bTenderApplicationDocumentResource::collection($this->documents),
            'criteria' => pb2bTenderApplicationCriterionResource::collection($this->criteria),
        );
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function mapItems(): array
    {
        $out = array();
        foreach ($this->items as $item) {
            $resource = new pb2bTenderApplicationItemResource($item);
            $out[] = $resource->withPrices($this->include_prices)->resolve();
        }

        return $out;
    }

    /**
     * @return array{id: int, code: string, name: string}
     */
    private static function codePayload(string $option, string $code): array
    {
        $id = 0;
        $name = '';
        foreach ((array) pb2bWaproHelper::getConfigOption($option) as $row) {
            if (!is_array($row) || (string) ($row['code'] ?? '') !== $code) {
                continue;
            }
            $id = (int) ($row['id'] ?? 0);
            $name = (string) ($row['name'] ?? '');
            break;
        }

        return array(
            'id' => $id,
            'code' => $code,
            'name' => $name,
        );
    }
}
