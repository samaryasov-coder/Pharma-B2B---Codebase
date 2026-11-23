<?php

class pb2bPrequalItemCollection extends pb2bWaproCollection
{
    public function getByPrequalId(int $prequal_id): array
    {
        if ($prequal_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('prequal_id' => array('value' => $prequal_id)));

        $this->model->setJoin(array(
            array('right' => new pb2bFileLinksModel(), 'as' => 'FL', 'type' => 'LEFT', 'on' => array('file_link_id' => 'id')),
        ));
        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null, 'prequal_id' => null, 'name' => null, 'qty' => null,
                'unit' => null, 'unit_id' => null, 'file_set_id' => null, 'file_link_id' => null,
                'comment' => null, 'sort' => null, 'create_datetime' => null, 'update_datetime' => null,
                array('field' => 'filename', 'table' => 'FL', 'as' => 'filename'),
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
