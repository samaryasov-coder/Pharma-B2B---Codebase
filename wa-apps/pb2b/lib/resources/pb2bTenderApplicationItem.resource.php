<?php

class pb2bTenderApplicationItemResource extends pb2bBaseJsonResource
{
    private bool $include_prices = false;

    protected array $casts = [
        'int' => ['id', 'application_id', 'tender_item_id', 'sort'],
        'string' => ['unit', 'vat_rate'],
    ];

    public function withPrices(bool $include = true): static
    {
        $this->include_prices = $include;

        return $this;
    }

    public function toArray(): array
    {
        $row = self::waproRow($this->resource);
        $qty = $row['qty'] ?? null;
        $out = array(
            'id' => (int) ($row['id'] ?? 0),
            'application_id' => (int) ($row['application_id'] ?? 0),
            'tender_item_id' => (int) ($row['tender_item_id'] ?? 0),
            'qty' => $qty,
            'unit' => $row['unit'] ?? null,
            'vat_rate' => $row['vat_rate'] ?? null,
            'sort' => (int) ($row['sort'] ?? 0),
        );
        if (!$this->include_prices) {
            return $out;
        }

        $price = $row['price_per_unit'] ?? null;
        $out['price_per_unit'] = $price;
        $out['amount'] = null;
        if ($qty !== null && $qty !== '' && $price !== null && $price !== '') {
            $out['amount'] = round((float) $qty * (float) $price, 2);
        }

        return $out;
    }

    /**
     * @param object|array|null $resource
     * @return array<string, mixed>
     */
    public static function waproRow(object|array|null $resource): array
    {
        if (is_array($resource)) {
            return $resource;
        }
        if (!is_object($resource)) {
            return array();
        }
        $data = $resource->data;
        $row = is_array($data) ? $data : array();
        if (empty($row['id'])) {
            $id = (int) $resource->id;
            if ($id > 0) {
                $row['id'] = $id;
            }
        }

        return $row;
    }
}
