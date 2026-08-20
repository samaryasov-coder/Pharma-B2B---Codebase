<?php

class pb2bCategoryModel extends pb2bWaproModel
{
    protected $table = 'pb2b_category';

    public function getBranchIdsByBounds(int $l, int $r): array
    {
        $this->queryReset();

        $this->setFetch('all', null, true);
        $this->setSelect(['id' => null]);

        $this->setWhere([
            'left_key'  => ['simile' => '>=', 'value' => $l],
            'right_key' => ['simile' => '<=', 'value' => $r],
        ]);

        return array_map('intval', $this->queryRun());
    }

    public function getBounds(int $category_id): ?array
    {
        $this->queryReset();

        $this->setFetch('assoc');
        $this->setSelect([
            'left_key'  => null,
            'right_key' => null,
        ]);

        $this->setWhere([
            'id' => ['simile' => '=', 'value' => $category_id],
        ]);

        $row = $this->queryRun();
        return $row ?: null;
    }
}
