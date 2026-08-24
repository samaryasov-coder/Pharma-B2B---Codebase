<?php

abstract class waproCategory extends waproObject
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
        $this->categoryItemsModel = waproHelper::getModel(waproHelper::getClassName($this).'Items');
        parent::__construct($id);
        $this->setSubject();
        $this->class_name = 'category';
    }

    protected function setSubject()
    {
        $subject = $this->app_id.ucfirst(str_replace('Category', '', $this->class_name));
        if (class_exists($subject)) {
            $subject = new $subject();
            if ($subject instanceof waproObject) {
                $this->subject = $subject;
            }
        }

    }

    /**
     * @param $data
     * @return array
     */
    protected function preSave(&$data): array
    {
        $result = parent::preSave($data);
        if (!$result['error']) {
            $data['full_url'] = $this->fullUrl($data['url'] ?? '');
            if (!$this->id) {
                $result['new'] = true;
            }
        }
        if ($this->data) {
            $result['old_parent_id'] = $this->data['parent_id'];
        }
        return $result;
    }

    /**
     * @param $result
     * @throws waDbException
     * @throws waException
     */
    protected function afterSave(&$result)
    {
        parent::afterSave($result);
        if (isset($result['new'])) {
            $result['reload_url'] = '#/categoryEdit/'.$this->id.'/';
        }
        $this->rebuild();
        if (isset($result['old_parent_id']) && $result['old_parent_id'] != $this->data['parent_id']) {
            $this->recount();
        }
    }

    /**
     * @param $result
     * @throws waDbException
     * @throws waException
     */
    protected function afterDelete(&$result)
    {
        parent::afterDelete($result);
        $result['reload_url'] = '#';
        if (!empty($this->id)) {
            $children = $this->getCollection('parent_id='.$this->id)->hash;
            $this->categoryItemsModel->deleteByField('category_id', $this->id);
            if (!empty($children)) {
                $class_name = $this->app_id.ucfirst($this->subject->class_name).$this->class_name;
                if ($this instanceof $class_name) {
                    foreach ($children as $child_id) {
                        $child = new $class_name($child_id);
                        $child->delete();
                    }
                }
            }
        }
        if (empty($this->data['parent_id']) || $this->model->getById($this->data['parent_id'])) {
            $this->rebuild();
            $this->recount();
        }
    }

    /**
     * @throws waDbException
     * @throws waException
     */
    protected function rebuild()
    {
        $tree = $this->getCollection()->getCollection(array('order' => array('parent_id' => 'ASC', 'left_key' => 'ASC')));
        $tree = new waproTree($tree);
        foreach ($tree->basic_tree as $item) {
            $this->model->setUpdateRow(array(
                'data' => array('left_key' => $item['left_key'], 'right_key' => $item['right_key'], 'depth' => $item['depth']),
                'where' => array('id' => $item['id'])
            ));
        }
        $this->model->multiUpdate();
    }

    /**
     * @throws waDbException
     * @throws waException
     */
    public function recount()
    {
        $categories = $this->getCollection()->getCollection(array('order' => array('parent_id' => 'DESC')));
        if (!empty($categories)) {
            $this->categoryItemsModel->setFetch('all', 'category_id', 1);
            $this->categoryItemsModel->setSelect(array('category_id' => null, 'COUNT(*)' => null));
            $this->categoryItemsModel->setGroupBy(array('category_id'));
            $counts = $this->categoryItemsModel->queryRun();
            foreach ($categories as $category) {
                if (empty($counts[$category['id']])) {
                    $counts[$category['id']] = 0;
                }
                if ($counts[$category['id']] > 0 && $category['parent_id']) {
                    if (empty($counts[$category['parent_id']])) {
                        $counts[$category['parent_id']] = 0;
                    }
                    $counts[$category['parent_id']] += $counts[$category['id']];
                }
                if ($counts[$category['id']] != $category['count']) {
                    $this->model->setUpdateRow(array(
                        'data' => array('count' => $counts[$category['id']]),
                        'where' => array('id' => $category['id']),
                    ));
                }
            }
            $this->model->multiUpdate();
        }
    }

    /**
     * @param string $url
     * @return string
     */
    public function fullUrl(string $url = ''): string
    {
        if (!empty($url) && !empty($this->data['parent_id'])) {
            $category = $this->app_id.ucfirst($this->subject->class_name).ucfirst($this->class_name);
            if (class_exists($category)) {
                $category = new $category($this->data['parent_id']);
                if ($category instanceof waproCategory) {
                    $parent_url = $category->fullUrl($category->data['url']);
                    $url = $parent_url.'/'.$url;
                }
            }
        }
        return $url;
    }
}