<?php
class pb2bDocflowRequestHistory extends pb2bWaproObject
{
    public function __construct(?int $id = null)
    {
        $this->model = new pb2bDocflowRequestHistoryModel();
        parent::__construct($id);
    }

    /**
     * Удаляет все элементы истории переданного запроса
     */
    static public function deleteByRequest(int $request_id): void
    {
        (new self)->model->deleteByField('request_id', $request_id);
    }
}