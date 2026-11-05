<?php

class pb2bTenderDocumentCollection extends pb2bWaproCollection
{
    protected pb2bFileLinksModel $fileLinksModel;

    public function __construct($hash = null)
    {
        $this->class_name = 'tender_document';
        $this->model = new pb2bTenderDocumentModel();
        $this->fileLinksModel = new pb2bFileLinksModel();
        parent::__construct($hash);
    }

    public function getByTenderId(int $tender_id, ?string $kind = null): array
    {
        if ($tender_id <= 0) {
            return array();
        }

        $this->model->setWhere(array(
            'tender_id' => array('value' => $tender_id),
        ));
        if ($kind !== null && $kind !== '') {
            $this->model->addWhere(array('kind' => array('value' => $kind)));
        }
        $this->model->setJoin(array(
            array(
                'right' => $this->fileLinksModel,
                'on' => array('file_link_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'FL',
            ),
            array(
                'left' => 'FL',
                'right' => new pb2bFileModel(),
                'on' => array('file_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'F',
            ),
        ));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'tender_id' => null,
                'name' => null,
                'kind' => null,
                'description' => null,
                'is_required' => null,
                'file_link_id' => null,
                'sort' => null,
                array('field' => 'filename', 'table' => 'FL', 'as' => 'file_name'),
                array('field' => 'size', 'table' => 'F', 'as' => 'file_size'),
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
