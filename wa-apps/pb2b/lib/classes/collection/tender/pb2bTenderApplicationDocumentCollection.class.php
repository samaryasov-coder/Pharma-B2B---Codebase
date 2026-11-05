<?php

class pb2bTenderApplicationDocumentCollection extends pb2bWaproCollection
{
    public function __construct($hash = null)
    {
        $this->class_name = 'tender_application_document';
        $this->model = new pb2bTenderApplicationDocumentModel();
        parent::__construct($hash);
    }

    public function getByApplicationId(int $application_id): array
    {
        if ($application_id <= 0) {
            return array();
        }
        return $this->getByApplicationIds(array($application_id));
    }

    public function getByApplicationIds(array $application_ids): array
    {
        $application_ids = array_values(array_filter(array_unique(array_map('intval', $application_ids)), static fn(int $id): bool => $id > 0));
        if (!$application_ids) {
            return array();
        }
        $this->model->setWhere(array('application_id' => array('simile' => 'IN', 'value' => $application_ids)));
        $this->model->setJoin(array(array(
            'right' => new pb2bFileLinksModel(),
            'on' => array('file_link_id' => 'id'),
            'type' => 'LEFT',
            'as' => 'FL',
        )));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'application_id' => null,
                'tender_document_id' => null,
                'name' => null,
                'comment' => null,
                'file_link_id' => null,
                array('field' => 'filename', 'table' => 'FL', 'as' => 'file_name'),
                'sort' => null,
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
