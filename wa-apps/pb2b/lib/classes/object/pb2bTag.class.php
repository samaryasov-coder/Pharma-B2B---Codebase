<?php

class pb2bTag extends pb2bWaproObject
{
    /**
     * @var pb2bTagItemsModel
     */
    protected pb2bTagItemsModel $tagItemsModel;

    public function __construct(?int $id = null)
    {
        $this->tagItemsModel = new pb2bTagItemsModel();
        parent::__construct($id);
    }

    protected function preSave(array &$data): array
    {
        $result = parent::preSave($data);
        if (empty($result['error']) && $this->checkTag($data['name'])) {
            $result = array('error' => true, 'message' => 'Существует другой такой тег');
        }
        return $result;
    }

    protected function afterSave(array &$result): void
    {
        parent::afterSave($result);
        if (empty($result['error']) && isset($result['new'])) {
            $result['dispatch_url'] = '#/tag/edit/id='.$this->id;
        }
    }

    protected function afterDelete(array &$result): void
    {
        parent::afterDelete($result);
        if (empty($result['error'])) {
            $this->tagItemsModel->deleteByField('tag_id', $this->id);
        }
    }

    /**
     * @param string $tag
     * @return ?int
     * @throws waDbException
     */
    protected function checkTag(string $tag): ?int
    {
        $this->model->setFetch('field');
        $this->model->setSelect(array('id' => null));
        $this->model->setWhere(array('name' => array('simile' => '=', 'value' => $tag)));
        if ($this->id) {
            $this->model->addWhere(array('id' => array('simile' => '!=', 'value' => $this->id)));
        }
        return $this->model->queryRun();
    }
}