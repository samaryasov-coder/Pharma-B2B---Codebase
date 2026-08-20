<?php
class pb2bFrontendApiBuyerDocflowRequestFilesDownloadController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $request_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $docflowRequest = new pb2bDocflowRequest($request_id);
        if (!$docflowRequest->id)
            return $this->response = ['result' => 0, 'message' => 'Не найден запрос'];

        $data = $docflowRequest['data'];
        $procedure_code = $data['procedure_code'];
        $fileSet = new waproFileSet(intval($data['file_set_id'] ?? 0));
        if (empty($fileSet->getId()))
            return $this->response = ['result' => 0, 'message' => 'Файлы не найдены'];

        $item_files = $fileSet->getFiles();

        $zip_path = tempnam(sys_get_temp_dir(), 'zip_');
        $zip = new ZipArchive();
        if ($zip->open($zip_path, ZipArchive::OVERWRITE) !== true)
            return $this->response = ['result' => 0, 'message' => 'Не удалось сформировать ZIP архив'];

        foreach ($item_files as $item_file) {
            $download_data = $item_file['download_data'] ?? null;
            if (!$download_data)
                continue;

            $filename = $item_file['filename'];
            $path = $download_data['path'];
            if (file_exists($path))
                $zip->addFile($path, $filename);
        }
        $zip->close();

        waFiles::readFile($zip_path, "Запрос на одобрение $procedure_code.zip");
        unlink($zip_path);
    }
}