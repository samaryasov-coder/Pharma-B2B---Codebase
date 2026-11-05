<?php

class pb2bTenderItemResource extends pb2bBaseJsonResource
{
    private bool $include_initial_price = true;

    public function withInitialPrice(bool $include): static
    {
        $this->include_initial_price = $include;

        return $this;
    }

    public function toArray(): array
    {
        $row = $this->resource;
        $file_link_id = (int) ($row['file_link_id'] ?? 0);
        $file_name = (string) ($row['file_name'] ?? '');
        $result = array(
            'id' => (int) ($row['id'] ?? 0),
            'name' => (string) ($row['name'] ?? ''),
            'qty' => $row['qty'] ?? null,
            'unit' => $row['unit'] ?? null,
            'max_price_no_vat' => $row['max_price_no_vat'] ?? null,
            'vat_rate' => $row['vat_rate'] ?? null,
            'delivery_place' => $row['delivery_place'] ?? null,
            'comment' => $row['comment'] ?? null,
            'file_link_id' => $file_link_id > 0 ? $file_link_id : null,
            'file_name' => $file_name,
            'sort' => (int) ($row['sort'] ?? 0),
        );
        if (!$this->include_initial_price) {
            unset($result['max_price_no_vat']);
        }

        return $result;
    }
}
