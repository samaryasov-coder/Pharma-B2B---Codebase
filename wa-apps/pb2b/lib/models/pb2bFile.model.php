<?php

class pb2bFileModel extends pb2bWaproModel
{
    protected $table = 'pb2b_file';

    /**
     * Возвращает найденный файл по хэшу
     */
    public function findByHash(string $hash): ?pb2bFile
    {
        $file_data = $this->getByField(['hash' => $hash]);
        if ($file_data)
            return new pb2bFile((int) $file_data['id']);

        return null;
    }
}