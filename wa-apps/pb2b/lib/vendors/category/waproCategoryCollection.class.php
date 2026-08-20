<?php

abstract class waproCategoryCollection extends waproCollection
{
    /**
     * @var waproModel
     */
    protected $categoryItemsModel;
    /**
     * @param null $id
     * @throws waException
     */
    public function __construct($id = null)
    {
        $this->categoryItemsModel = waproHelper::getModel(str_replace('Collection', '', waproHelper::getClassName($this)).'Items');
        parent::__construct($id);
        $this->class_name = 'category';
    }

    /**
     * @param $values
     * @param array|string $filtered
     * @return array|false|int|mixed|string
     * @throws waDbException
     */
    protected function filterByItem($values, $filtered = array())
    {
        $this->categoryItemsModel->setFetch('all', 'id', 1);
        $this->categoryItemsModel->setSelect(array('category_id' => array('id', 'tmp')));
        if ($filtered != 'all') {
            $values[' category_id'] = array('simile' => 'IN', 'value' => $filtered);
        }
        $this->categoryItemsModel->setWhere($values);
        return $this->categoryItemsModel->queryRun();
    }

    /**
     * @param $items
     * @param array $params
     */
    protected function workup(&$items, array $params = array())
    {
        parent::workup($items, $params);
        foreach ($items as $item) {
            if (!empty($item['parent_id']) && isset($items[$item['parent_id']])) {
                if (empty($items[$item['parent_id']]['children_count'])) {
                    $items[$item['parent_id']]['children_count'] = 0;
                }
                $items[$item['parent_id']]['children_count']++;
            }
        }
    }
}