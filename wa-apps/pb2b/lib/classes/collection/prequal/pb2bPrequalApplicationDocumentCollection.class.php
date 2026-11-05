<?php

class pb2bPrequalApplicationDocumentCollection extends pb2bWaproCollection
{
    public function getByApplicationId(int $application_id): array
    {
        if ($application_id <= 0) {
            return array();
        }
        $this->model->setWhere(array('application_id' => array('value' => $application_id)));
        $this->model->setJoin(array(
            array(
                'right' => new pb2bPrequalDocumentModel(),
                'on' => array('prequal_document_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'PD',
            ),
            array(
                'right' => new pb2bFileLinksModel(),
                'on' => array('file_link_id' => 'id'),
                'type' => 'LEFT',
                'as' => 'FL',
            ),
        ));

        return $this->getCollection(array(
            'key' => false,
            'select' => array(
                'id' => null,
                'application_id' => null,
                'prequal_document_id' => null,
                'file_link_id' => null,
                'provider_comment' => null,
                'status' => null,
                'uploaded_at' => null,
                'valid_until' => null,
                'review_comment' => null,
                'reviewed_by_contact_id' => null,
                'reviewed_at' => null,
                'create_datetime' => null,
                'update_datetime' => null,
                array('field' => 'name', 'table' => 'PD', 'as' => 'document_name'),
                array('field' => 'description', 'table' => 'PD', 'as' => 'document_description'),
                array('field' => 'filename', 'table' => 'FL', 'as' => 'file_name'),
            ),
            'order' => array('id' => 'ASC'),
        ));
    }
}
