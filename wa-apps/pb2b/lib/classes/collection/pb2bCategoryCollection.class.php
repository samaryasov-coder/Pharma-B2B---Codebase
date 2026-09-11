<?php

class pb2bCategoryCollection extends pb2bWaproCollection
{
    protected function addWhereCompany($where) 
    {
        $model = new pb2bCompanyCategoryModel();
        $this->model->addJoin(array(
            array('right' => $model, 'on' => array('id' => 'category_id'))
        ));
        $this->model->addWhere($where);
    }

    /**
     * Плоское дерево для кабинета (ЗЦ): id / parent_id / name / depth.
     *
     * @return array<int, array{id:int,parent_id:int,name:string,depth:int}>
     */
    public function getCabinetTreeRows(): array
    {
        $model = new pb2bCategoryModel();
        $rows = $model->query(
            'SELECT id, parent_id, name, depth
             FROM pb2b_category
             ORDER BY left_key ASC, id ASC'
        )->fetchAll();

        if (!is_array($rows) || empty($rows)) {
            return array();
        }

        $result = array();
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $result[] = array(
                'id' => (int) ($row['id'] ?? 0),
                'parent_id' => (int) ($row['parent_id'] ?? 0),
                'name' => (string) ($row['name'] ?? ''),
                'depth' => (int) ($row['depth'] ?? 0),
            );
        }

        return $result;
    }
}