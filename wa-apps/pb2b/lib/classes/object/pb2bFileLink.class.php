<?php
class pb2bFileLink extends pb2bWaproObject
{
    protected function afterDelete(array &$result): void
    {
        if ($result['error']) return;

        $file = $this->getFile();
        if ($file && !self::fileHasLinks($file->id))
            $file->delete();
    }



    /**
     * Проверяет наличие записей-ссылок на указанный файл
     */
    public static function fileHasLinks(int $file_id): bool
    {
        return (new self())->model->existsByField('file_id', $file_id);
    }


    public function __construct(?int $id = null)
    {
        $this->model = new pb2bFileLinksModel();
        parent::__construct($id);
    }

    /**
     * Обновляет ссылку на файл, если она изменилась, и удаляет предыдущий файл (если это возможно).
     *
     * Также обновляет отображаемое имя файла.
     */
    public function updateFile(pb2bFile $new_file, string $filename): void
    {
        $file_to_delete = null;
        $data = ['filename' => $filename,];
        $old_file = $this->getFile();

        if ($new_file->id && $new_file->id !== $old_file?->id) {
            $data['file_id'] = $new_file->id;
            $file_to_delete = $old_file;
        }

        $this->save($data);
        if ($file_to_delete && !self::fileHasLinks($file_to_delete->id))
            $file_to_delete->delete();
    }

    /**
     * Возвращает файл с добавленным названием (filename)
     */
    public function getFile(): ?pb2bFile
    {
        $file = new pb2bFile($this->data['file_id']);
        if ($file->id){
            $file->setField('filename', $this->data['filename']);
            return $file;
        }

        return null;
    }
}