<?php

class waproFileSet extends waproItemSet
{
	public function __construct($id = null, $block_id = null)
    {
		$this->model = new waproFileSetModel();
		$this->items_model = new waproFileSetItemsModel();
		parent::__construct($id);
	}

	public function getImages($raw = false, $sizes = array(), $start = 0, $limit = null)
	{
		$items = parent::getItems($raw, $start, $limit);
		if($raw) {return $items;}
		if(!count($items)) {return $items;}
		
		return $items;
	}

    public function getFiles($raw = false, $start = 0, $limit = null)
    {
        $items = parent::getItems($raw, $start, $limit);
        if($raw) {return $items;}
        if(!count($items)) {return $items;}
        foreach($items as $key => $item) {$items[$key]['download_data'] = self::getDownloadData($this->app_id, $item['id']);}

        return $items;
    }

    public function getFile($item_id, $raw = false)
    {
        $item = parent::getItem($item_id, $raw = false);
        if($raw) {return $item;}
        if(!$item) {return $item;}

        $item['download_data'] = self::getDownloadData($this->app_id, $item['id']);
        return $item;
    }
	
	public function deleteItem($item_id)
	{
		if(!$this->id) {return;}
		$item_data = $this->items_model->getById($item_id);
		if(!$item_data) {return;}
		if($item_data['set_id'] != $this->id) {return;}
		$path = self::getPath($this->app_id, $this->id, $item_data['is_public'], $item_data['id'], $item_data['ext']);
		if(file_exists($path)) {waFiles::delete($path);}
		$this->items_model->deleteById($item_id);
	}
	
	public function clearItems()
	{
		if(!$this->id) {return;}
		$items = $this->getItems();
		if(!count($items)) {return;}
		
		foreach($items as $item)
		{
			$path = self::getPath($this->app_id, $this->id, $item['is_public'], $item['id'], $item['ext']);
			if(file_exists($path)) {waFiles::delete($path);}
		}
		
		$this->items_model->clearBySet($this->id);
	}
	
	public function makeClone()
	{
		$classname = get_class($this);
		$clone = new $classname();
		
		$items = $this->getItems(true);
		if(!count($items)) {return $clone;}
		$clone_id = $clone->getId();
		
		foreach($items as $key => $item)
		{
			$new_item_id = $this->items_model->addRawItem($clone_id, $item);
			$path = self::getPath($this->app_id, $this->id, $item['is_public'], $item['id'], $item['ext']);
			$new_path = self::getPath($this->app_id, $clone_id, $item['is_public'], $new_item_id, $item['ext']);
			try {waFiles::copy($path, $new_path);}
			catch(Exception $e) {$this->items_model->deleteById($new_item_id);}
		}
		
		return $clone;
	}
	
	public function uploadFromPost($vname, $options = array())
	{
		$files = waRequest::file($vname);
		if(!$this->id)
		{
			$result = array();
			foreach($files as $file)
			{
				$result[] = array('result' => 0, 'message' => 'Ошибка инициализации коллекции изображений');
			}
			return $result;
		}
		$result = array();
		
		$is_public = 1;
		if (isset($options['is_public'])) {
			$is_public = (int) $options['is_public'];
		}
				
		foreach($files as $file)
		{
			if(!$file->uploaded())
			{
				$result[] = array('result' => 0, 'message' => 'Не удалось загрузить файл. Проверьте лимиты на размер файла (MAX_UPLOAD_FILESIZE)');
				continue;
			}
			try
			{
				$data = array(
					'set_id' => $this->id,
					'name' => '',
					'description' => '',
					'filename' => '',
					'original_filename' => basename($file->name),
					'ext' => $file->extension,
					'is_public' => $is_public,
				);
				if(isset($options['allowed_extensions']))
				{
					if(!is_array($options['allowed_extensions'])) {$options['allowed_extensions'] = array($options['allowed_extensions']);}
					if(!in_array($data['ext'], $options['allowed_extensions']))
					{
						$result[] = array('result' => 0, 'message' => 'Недопустимое расширение файла');
						continue;
					}
				}

				if (isset($options['extra'])) {
					$data['extra'] = $options['extra'];
				}
				
				$file_id = $this->addItem($data);
				$path = self::getPath($this->app_id, $this->id, $is_public);
				try {
					if((file_exists($path) && !is_writable($path)) || (!file_exists($path) && !waFiles::create($path))) 
					{
						$this->items_model->deleteById($file_id);
						$result[] = array('result' => 0, 'message' => 'Ошибка записи файла. Проверьте права на запись.');
					}
					else
					{
						$filename = $file_id.'.'.$file->extension;
						$file->moveTo($path, $file_id.'.'.$file->extension);
						$this->items_model->updateById($file_id, array('filename' => basename($file->name), 'name' => basename($file->name)));
						$result[] = array('result' => 1, 'message' => 'Файл загружен', 'file_id' => $file_id, 'download_data' => self::getDownloadData($this->app_id, $file_id));
					}
				}
				catch (Exception $e)
				{
					$this->items_model->deleteById($file_id);
					$result[] = array('result' => 0, 'message' => $e->getMessage());
				}
			}
			catch (Exception $e)
			{
				$result[] = array('result' => 0, 'message' => $e->getMessage());
			}
		}
		
		return $result;
	}
	
	static public function getPath($app_id, $set_id, $is_public, $file_id = null, $ext = null)
	{
		$str = str_pad($set_id, 6, '0', STR_PAD_LEFT);
        $path = wa()->getDataPath('files/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), $is_public, $app_id, false);
		if(!$file_id) {return $path;}
		return $path.'/'.$file_id.'.'.$ext;
	}

	static public function getDownloadData($app_id, $file_id)
	{
		$items_model = new waproFileSetItemsModel();
		$file = $items_model->getById($file_id);
		if(!$file) {return array('result' => 0, 'message' => 'Файл не найден');}
		
		$path = self::getPath($app_id, $file['set_id'], $file['is_public'], $file['id'], $file['ext']);
		if(!file_exists($path)) {return array('result' => 0, 'message' => 'Файл был удален с сервера');}

		
		
		$direct_url = '';
		$str = str_pad($file['set_id'], 6, '0', STR_PAD_LEFT);
		if($file['is_public']) {$direct_url = wa()->getDataUrl('files/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2).'/'.$file['id'].'.'.$file['ext'], true, $app_id, true);}
		
		return array(
			'result' => 1,
			'original_name' => $file['original_filename'],
			'original_name_escaped' => htmlspecialchars($file['original_filename'], ENT_QUOTES),
			'user_name' => ifempty($file['name'], $file['original_filename']),
			'user_name_escaped' => htmlspecialchars(ifempty($file['name'], $file['original_filename'])),
			'download_name' => ifempty($file['filename'], $file['original_filename']),
			'download_name_escaped' => htmlspecialchars(ifempty($file['filename'], $file['original_filename']), ENT_QUOTES),
			'path' => $path,
			'backend_url' => wa()->getAppUrl($app_id).'?module=files&action=download&file_id='.$file['id'],
			'backend_url_escaped' => htmlspecialchars(wa()->getAppUrl($app_id).'?module=files&action=download&file_id='.$file['id'], ENT_QUOTES),
			'frontend_url' => wa()->getRouteUrl($app_id.'/frontend/downloadFile', array('file_id' => $file['id']), true),
			'frontend_url_escaped' => htmlspecialchars(wa()->getRouteUrl($app_id.'/frontend/downloadFile', array('file_id' => $file['id']), true), ENT_QUOTES),
			'direct_url' => $direct_url,
			'direct_url_escaped' => htmlspecialchars($direct_url, ENT_QUOTES),
		);
	}
}