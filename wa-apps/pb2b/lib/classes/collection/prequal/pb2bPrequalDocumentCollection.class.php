<?php

class pb2bPrequalDocumentCollection extends pb2bWaproCollection
{
    public function getByPrequalId(int $prequal_id, ?string $kind = null): array
    {
        if ($prequal_id <= 0) return array();
        $this->model->setWhere(array('prequal_id' => array('value' => $prequal_id)));
        if ($kind !== null) $this->model->addWhere(array('kind' => array('value' => $kind)));
        $this->model->setJoin(array(
            array('right' => new pb2bFileLinksModel(), 'as' => 'FL', 'type' => 'LEFT', 'on' => array('file_link_id' => 'id')),
            array('left' => 'FL', 'right' => new pb2bFileModel(), 'as' => 'F', 'type' => 'LEFT', 'on' => array('file_id' => 'id')),
        ));
        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null, 'prequal_id' => null, 'kind' => null, 'name' => null,
                'description' => null, 'is_required' => null, 'file_link_id' => null, 'sort' => null,
                array('field' => 'filename', 'table' => 'FL', 'as' => 'filename'),
                array('field' => 'size', 'table' => 'F', 'as' => 'size'),
                array('field' => 'ext', 'table' => 'F', 'as' => 'ext'),
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
