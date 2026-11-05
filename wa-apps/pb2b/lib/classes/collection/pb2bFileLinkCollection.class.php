<?php

class pb2bFileLinkCollection extends pb2bWaproCollection
{
    public function __construct($hash = null)
    {
        $this->model = new pb2bFileLinksModel();
        parent::__construct($hash);
    }

    public function getByIds(array $ids): array
    {
        if (!$ids) {
            return array();
        }
        $this->model->setWhere(array(
            'id' => array('simile' => 'IN', 'value' => $ids),
        ));
        $this->model->setJoin(array(
            array(
                'right' => new pb2bFileModel(),
                'on' => array('file_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'F',
            ),
        ));

        return $this->getCollection(array(
            'key' => 'id',
            'select' => array(
                'id' => null,
                'file_id' => null,
                'filename' => null,
                'owner_company_id' => null,
                'extra' => null,
                array('field' => 'id', 'table' => 'F', 'as' => 'stored_file_id'),
                array('field' => 'storage_disk', 'table' => 'F', 'as' => 'storage_disk'),
                array('field' => 'storage_path', 'table' => 'F', 'as' => 'storage_path'),
            ),
        ));
    }
}
