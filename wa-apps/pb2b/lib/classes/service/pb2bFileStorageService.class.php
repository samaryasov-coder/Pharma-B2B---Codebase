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

    private function createLinkToFile(pb2bFile $file, string $filename, ?int $owner_company_id = null, array $extra = array()): ?pb2bFileLink
    {
        if (!$file->id)
            return null;

        $file_link = new pb2bFileLink();
        $data = array(
            'file_id' => $file->id,
            'filename' => $filename,
            'owner_company_id' => $owner_company_id,
        );
        if ($extra) {
            $data['extra'] = json_encode($extra, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        }
        $result = $file_link->save($data);
        if (!empty($result['error']) || !$file_link->id) {
            throw new waException('Ошибка создания ссылки на файл', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
        }

        return $file_link;
    }


    public function __construct()
    {
        $this->fileModel = new pb2bFileModel();
    }

    public function validateDocumentUpload(waRequestFile $upload_file): array
    {
        if (!$upload_file->uploaded()) {
            throw new waException('Файл не загружен', pb2bHttpStatus::BAD_REQUEST);
        }

        $ext = strtolower((string) $upload_file->extension);
        $allowed = array('pdf', 'doc', 'docx');
        if (!in_array($ext, $allowed, true)) {
            throw new waException(
                'Допустимые форматы: PDF, DOC, DOCX',
                pb2bHttpStatus::BAD_REQUEST
            );
        }

        $size = filesize($upload_file->tmp_name);
        if ($size === false || $size <= 0 || $size > 10 * 1024 * 1024) {
            throw new waException(
                'Файл должен быть непустым и не превышать 10 МБ',
                pb2bHttpStatus::BAD_REQUEST
            );
        }
        if (mb_strlen((string) $upload_file->name) > 255) {
            throw new waException('Название файла не должно превышать 255 символов', pb2bHttpStatus::BAD_REQUEST);
        }
        $mime = strtolower((string) mime_content_type($upload_file->tmp_name));
        $allowed_mimes = array(
            'pdf' => array('application/pdf'),
            'doc' => array('application/msword', 'application/vnd.ms-office', 'application/x-ole-storage', 'application/cdfv2'),
            'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip', 'application/x-zip-compressed'),
        );
        if (!in_array($mime, $allowed_mimes[$ext], true)) {
            throw new waException('Содержимое файла не соответствует формату PDF, DOC или DOCX', pb2bHttpStatus::BAD_REQUEST);
        }
        if ($ext === 'doc') {
            $contents = file_get_contents($upload_file->tmp_name);
            if ($contents === false
                || substr($contents, 0, 8) !== hex2bin('d0cf11e0a1b11ae1')
                || strpos($contents, "W\0o\0r\0d\0D\0o\0c\0u\0m\0e\0n\0t\0") === false
            ) {
                throw new waException('Файл не является документом DOC', pb2bHttpStatus::BAD_REQUEST);
            }
            unset($contents);
        }
        if ($ext === 'docx') {
            if (!class_exists('ZipArchive')) {
                throw new waException('Проверка DOCX недоступна на сервере', pb2bHttpStatus::INTERNAL_SERVER_ERROR);
            }
            $archive = new ZipArchive();
            if ($archive->open($upload_file->tmp_name) !== true) {
                throw new waException('Некорректный файл DOCX', pb2bHttpStatus::BAD_REQUEST);
            }
            try {
                $is_docx = $archive->locateName('[Content_Types].xml') !== false
                    && $archive->locateName('word/document.xml') !== false;
            } finally {
                $archive->close();
            }
            if (!$is_docx) {
                throw new waException('Архив не является документом DOCX', pb2bHttpStatus::BAD_REQUEST);
            }
        }

        return array('ext' => $ext, 'size' => (int) $size);
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
    public function saveFileAndCreateLink(waRequestFile $upload_file, string $path, ?int $owner_company_id = null, array $extra = array()): pb2bFileLink
    {
        if (!$upload_file->uploaded())
            throw new waException('Файл не загружен', pb2bHttpStatus::BAD_REQUEST);

        $file = $this->getOrCreateFile($upload_file, $path);
        if (!$file)
            throw new waException('Ошибка сохранения файла', pb2bHttpStatus::INTERNAL_SERVER_ERROR);

        $file_link = $this->createLinkToFile($file, $upload_file->name, $owner_company_id, $extra);
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