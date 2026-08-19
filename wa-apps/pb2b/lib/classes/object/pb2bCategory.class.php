<?php

class pb2bCategory extends pb2bWaproObject
{
    protected function preSave(array &$data): array
    {
        $result = parent::preSave($data);
        if (!$result['error']) {
            if (empty($this->id) && !empty($data['parent_id'])) {
                $parent = new pb2bCategory($data['parent_id']);
                $data['left_key'] = $parent->data['left_key'];
                $data['depth'] = $parent->data['depth'] + 1;
            }
        }
        return $result;
    }

    /**
     * @param array $result
     * @return void
     * @throws waDbException
     * @throws waException
     */
    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);
        if (!$result['error']) {
            if (isset($result['new']) && $this->data['parent_id'] > 0) {
                $category = new pb2bCategory($this->data['parent_id']);
                $category->setState(1);
            }
            $this->rebuild();
        }
    }

    /**
     * @param array $result
     * @return void
     * @throws waDbException
     * @throws waException
     */
    protected function afterDelete(array &$result): void
    {
        parent::afterDelete($result);
        if (!$result['error']) {
            $this->model->setFetch('all', 'id', 1);
            $this->model->setSelect(array('id' => array('id', 'tmp')));
            $this->model->setWhere(array(
                'parent_id' => array('simile' => '=', 'value' => $this->id),
            ));
            $ids = $this->model->queryRun();
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    $category = new pb2bCategory($id);
                    $category->delete(array('parent_delete' => true));
                }
            }
            if (empty($result['data']['parent_delete'])) {
                $this->rebuild();
            }
        }
    }

    /**
     * @return array
     * @throws waDbException
     */
    protected function getParents(): array
    {
        $this->model->setWhere(array(
            'left_key' => array('simile' => '<', 'value' => $this->data['left_key']),
            'right_key' => array('simile' => '>', 'value' => $this->data['right_key']),
        ));
        $this->model->setOrderBy(array('depth' => 'DESC'));
        return $this->model->queryRun();
    }

    /**
     * @throws waDbException
     * @throws waException
     */
    public function rebuild(): void
    {
        $tree = $this->getTree();
        foreach ($tree->basic_tree as $item) {
            $this->model->updateById($item['id'], array('left_key' => $item['left_key'], 'right_key' => $item['right_key'], 'depth' => $item['depth']));
        }
    }

    /**
     * @return pb2bWaproTree
     * @throws waDbException
     * @throws waException
     */
    public function getTree(): pb2bWaproTree
    {
        $tree = $this->getCollection()->getCollection(array('order' => array('parent_id' => 'ASC', 'left_key' => 'ASC', 'id' => 'ASC')));
        if (!empty($tree)) {
            $settingsModel = new waContactSettingsModel();
            $stages = $settingsModel->get(wa()->getUser()->getId(), 'pb2b');
            foreach ($tree as &$item) {
                if ($item['right_key'] - $item['left_key'] > 0) {
                    if (isset($stages['category_'.$item['id']])) {
                        $item['expanded'] = 1;
                    }
                }
                if ($item['parent_id'] > 0) {
                    if (!empty($tree[$item['parent_id']]['expanded'])) {
                        $item['parent_expanded'] = 1;
                    }
                }
            }
        }
        return new pb2bWaproTree($tree);
    }

    /**
     * @param $state
     * @return void
     * @throws waException
     */
    public function setState($state): void
    {
        $user_id = wa()->getUser()->getId();
        $settingsModel = new waContactSettingsModel();
        if ($state) {
            $settingsModel->set($user_id, 'pb2b', 'category_'.$this->id, 1);
        } else {
            $settingsModel->delete($user_id, 'pb2b', 'category_'.$this->id);
        }
    }

    /**
     * @param int $parent_id
     * @param int $before_id
     * @return false[]
     * @throws waException
     */
    public function move(int $parent_id, int $before_id = 0): array
    {
        $result = array('error' => false);
        $category_data = $this->data;
        if ($category_data) {
            $category_data['parent_id'] = $parent_id;
            if ($before_id) {
                $before = new pb2bCategory($before_id);
                if ($before->data) {
                    $category_data['left_key'] = $before->data['left_key'] - 1;
                } else {
                    $result = array('error' => true, 'message' => 'Ошибка получения классификатора, перед которым поставлен текущий');
                }
            } else {
                $parent = new pb2bCategory($category_data['parent_id']);
                if (empty($parent->data['right_key'])) {
                    $result = array('error' => true, 'message' => 'Ошибка получения родительского классификатора');
                } else {
                    $category_data['left_key'] = $parent->data['right_key'];
                }
            }
            if (!$result['error']) {
                $this->save($category_data);
            }
        } else {
            $result = array('error' => true, 'message' => 'Ошибка получения классификатора');
        }
        return $result;
    }
}