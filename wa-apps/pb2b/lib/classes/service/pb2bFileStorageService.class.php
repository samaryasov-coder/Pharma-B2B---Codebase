<?php
class pb2bFileStorageService
{
    private pb2bFileModel $fileModel;

    private function generateStoragePath(string $hash, string $path): string
    {
        return sprintf(
            '%s/%s/%s/%s/',
            rtrim($path, '/'),
            date('Y'),
            date('m'),
            substr($hash, 0, 2) . '/' . substr($hash, 2, 2),
        );
    }

    private function createLinkToFile(pb2bFile $file, string $filename, ?int $owner_company_id = null): ?pb2bFileLink
    {
        if (!$file->id)
            return null;

        $file_link = new pb2bFileLink();
        $file_link->save([
            'file_id' => $file->id,
            'filename' => $filename,
            'owner_company_id' => $owner_company_id
        ]);

        return $file_link;
    }


    public function __construct()
    {
        $this->fileModel = new pb2bFileModel();
    }

    public function cloneLink(pb2bFileLink $source_file_link): ?pb2bFileLink
    {
        if (!$source_file_link->id)
            return null;

        $file = $source_file_link->getFile();
        if (!$file)
            return null;


        return $this->createLinkToFile($file, $source_file_link->data['filename'], $source_file_link->data['owner_company_id']);
    }

    /**
     * Создать или получить уже существующий файл (по хэшу)
     * @throws waException
     */
    public function getOrCreateFile(waRequestFile $upload_file, string $path): ?pb2bFile
    {
        if (!$upload_file->uploaded())
            throw new waException('Файл не загружен', pb2bHttpStatus::BAD_REQUEST);

        $hash = hash_file('sha256', $upload_file->tmp_name);
        $file = $this->findByHash($hash);
        if ($file)
            return $file;

        $filepath = $this->generateStoragePath($hash, $path);
        $file_ext = strtolower($upload_file->extension);
        $filename = "$hash.$file_ext";

        $result_put = pb2bStorage::disk('local')->putFile($filepath, $upload_file, $filename);
        if ($result_put === false)
            return null;

        $file = new pb2bFile();
        try {
            $file->save([
                'original_name' => $upload_file->name,
                'mime_type' => mime_content_type($upload_file->tmp_name),
                'size' => $upload_file->size,
                'ext' => $file_ext,
                'storage_disk' => 'local',
                'storage_path' => $result_put,
                'hash' => $hash,
            ]);
        }catch (waDbException $e){
            if ($e->getCode() === 1062)
                return $this->findByHash($hash);
            throw $e;
        }

        return $file;
    }

    /**
     * Сохранить файл и создать ссылку на него
     * @throws waException
     */
    public function saveFileAndCreateLink(waRequestFile $upload_file, string $path, ?int $owner_company_id = null): pb2bFileLink
    {
        if (!$upload_file->uploaded())
            throw new waException('Файл не загружен', pb2bHttpStatus::BAD_REQUEST);

        $file = $this->getOrCreateFile($upload_file, $path);
        if (!$file)
            throw new waException('Ошибка сохранения файла', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

        $file_link = $this->createLinkToFile($file, $upload_file->name, $owner_company_id);
        if (!$file_link)
            throw new waException('Ошибка создания ссылки на файл', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

        return $file_link;
    }

    /**
     * Найти файл по хэшу
     */
    public function findByHash(string $hash): ?pb2bFile
    {
        return $this->fileModel->findByHash($hash);
    }
}