<?php
class pb2bFile extends pb2bWaproObject
{
    protected function preDelete(array &$data = array()): array
    {
        $result = parent::preDelete($data);
        if ($result['error']) return $result;

        $is_deleted = pb2bStorage::disk('local')->delete($this->data['storage_path']);
        $result['error'] = !$is_deleted;

        return $result;
    }



    public function __construct(?int $id = null)
    {
        parent::__construct($id);
    }
}