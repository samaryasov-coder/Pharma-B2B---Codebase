<?php

abstract class pb2bWaproTag
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $app_id, $class_name;
    /**
     * @var pb2bWaproModel
     */
    protected $model, $subjectModel;

    /**
     * @param int|null $id
     * @throws waException
     */
    public function __construct(int $id = null)
    {
        $this->id = intval($id);
        $this->app_id = waSystem::getInstance()->getAppInfo()['id'];
        if (empty($this->class_name)) {
            $this->class_name = str_replace('Tag', '', pb2bWaproHelper::getClassName($this));
        }
        if (!($this->model instanceof pb2bWaproModel)) {
            $model_name = $this->app_id . ucfirst($this->class_name) . 'TagModel';
            if (class_exists($model_name)) {
                $this->model = new $model_name();
            }
        }
        if (!($this->subjectModel instanceof pb2bWaproModel)) {
            $model_name = $this->app_id . ucfirst($this->class_name) . 'TagsModel';
            if (class_exists($model_name)) {
                $this->subjectModel = new $model_name();
            }
        }
    }

    /**
     * @param array $tags
     * @throws waDbException
     * @throws waException
     */
    public function save(array $tags)
    {
        $tags_values = $this->getTagsByNames($tags);
        $insert = array();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                if (empty($tags_values[$tag])) {
                    $insert[] = array('name' => $tag);
                }
            }
            if (!empty($insert)) {
                $this->model->multipleInsert($insert);
                $tags_values = $this->getTagsByNames($tags);
            }
        }
        if (!empty($this->id)) {
            $this->subjectModel->deleteByField('item_id', $this->id);
            if (!empty($tags)) {
                $insert = array();
                foreach ($tags as $tag) {
                    if (!empty($tags_values[$tag]['id'])) {
                        $insert[] = array('item_id' => $this->id, 'tag_id' => $tags_values[$tag]['id']);
                    }
                }
                $this->subjectModel->multipleInsert($insert);
            }
        }
        $this->recount();
    }

    /**
     * @throws waDbException
     */
    protected function recount()
    {
        $tags = $this->model->getAll();
        $this->subjectModel->setFetch('all', 'tag_id', 1);
        $this->subjectModel->setSelect(array('tag_id' => null, 'COUNT(*)' => null));
        $this->subjectModel->setGroupBy(array('tag_id'));
        $counts = $this->subjectModel->queryRun();
        foreach ($tags as $tag) {
            $count = $counts[$tag['id']] ?? 0;
            if ($tag['count'] != $count) {
                $this->model->updateById($tag['id'], array('count' => $count));
            }
        }
    }

    /**
     * @param array $tags
     * @return array
     * @throws waDbException
     */
    public function getTagsByNames(array $tags): array
    {
        $this->model->setFetch('all', 'name');
        if (!empty($tags)) {
            $this->model->setWhere(array('name' => array('simile' => 'IN', 'value' => $tags)));
        }
        return $this->model->queryRun();
    }

    /**
     * @return array
     * @throws waDbException
     */
    public function getSubjectTags(): array
    {
        $result = array();
        if (!empty($this->id)) {
            $this->model->setSelect(array($this->model->getTableName().'.*' => null));
            $this->model->setJoin(array(
                array('right' => $this->subjectModel->getTableName(), 'on' => array('id' => 'tag_id')),
            ));
            $this->model->setWhere(array('item_id' => array('simile' => '=', 'value' => $this->id)));
            $result = $this->model->queryRun();
        }
        return $result;
    }

    /**
     * @return array
     * @throws waDbException
     */
    public function getAllTags(): array
    {
        $this->model->setOrderBy(array('count' => 'DESC'));
        return $this->model->queryRun();
    }

    /**
     * @return array
     * @throws waDbException
     */
    public function getPopularTags(): array
    {
        $this->model->setOrderBy(array('count' => 'DESC'));
        $this->model->setLimit(10);
        return $this->model->queryRun();
    }
}