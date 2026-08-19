<?php
class pb2bFilesDownloadAction extends waViewAction
{
    public function execute()
    {
		$file_id = waRequest::get('file_id', 0, 'int');
		$data = waproFileSet::getDownloadData('pb2b', $file_id);
		if(!$data['result']) {$this->view->assign('error', $data['message']); return;}
		waFiles::readFile($data['path'], $data['download_name']);
    }
}
