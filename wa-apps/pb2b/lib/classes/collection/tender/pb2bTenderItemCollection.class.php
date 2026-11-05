<?php

class pb2bTenderItemCollection extends pb2bWaproCollection
{
    protected pb2bFileLinksModel $fileLinksModel;

    public function __construct($hash = null)
    {
        $this->class_name = 'tender_item';
        $this->model = new pb2bTenderItemModel();
        $this->fileLinksModel = new pb2bFileLinksModel();
        parent::__construct($hash);
    }

    public function getByTenderId(int $tender_id): array
    {
        if ($tender_id <= 0) return array();

        $this->model->setWhere(array(
            'tender_id' => array('value' => $tender_id),
        ));
        $this->model->setJoin(array(
            array(
                'right' => $this->fileLinksModel,
                'on' => array('file_link_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'FL',
            ),
        ));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'tender_id' => null,
                'name' => null,
                'qty' => null,
                'unit' => null,
                'max_price_no_vat' => null,
                'vat_rate' => null,
                'delivery_place' => null,
                'comment' => null,
                'file_link_id' => null,
                'sort' => null,
                array('field' => 'filename', 'table' => 'FL', 'as' => 'file_name'),
            ),
            'order' => array('sort' => 'ASC', 'id' => 'ASC'),
        ));
    }
}
