<?php

class waproImageSet extends waproItemSet
{
	public function __construct($id = null)
    {
		$this->model = new waproImageSetModel();
		$this->items_model = new waproImageSetItemsModel();
		parent::__construct($id);
	}
	
	public function getImages($raw = false, $sizes = array(), $start = 0, $limit = null)
	{
		$items = parent::getItems($raw, $start, $limit);
		if($raw) {return $items;}
		if(!count($items)) {return $items;}
		
		foreach($items as $key => $item)
		{
			$items[$key]['thumb'] = self::getThumbUrl($this->app_id, $item['set_id'], $item['id'], '0x200', $item['ext']);
			if(count($sizes))
			{
				foreach($sizes as $size)
				{
					$items[$key]['thumb_'.$size] = self::getThumbUrl($this->app_id, $item['set_id'], $item['id'], $size, $item['ext']);
				}
			}
		}
		
		return $items;
	}
	
	public function getImage($item_id, $raw = false, $sizes = array())
	{
		$item = parent::getItem($item_id, $raw = false);
		if($raw) {return $item;}
		if(!$item) {return $item;}
		
		$item['thumb'] = self::getThumbUrl($this->app_id, $item['set_id'], $item['id'], '0x200', $item['ext']);
		if(count($sizes))
		{
			foreach($sizes as $size)
			{
				$item['thumb_'.$size] = self::getThumbUrl($this->app_id, $item['set_id'], $item['id'], $size, $item['ext']);
			}
		}
		return $item;
	}
	
	public function getFirstImage($sizes = array())
	{
		$item = parent::getFirstItem();
		if(!$item) {return $item;}
		
		$item['thumb'] = self::getThumbUrl($this->app_id, $item['set_id'], $item['id'], '0x200', $item['ext']);
		if(count($sizes))
		{
			foreach($sizes as $size)
			{
				$item['thumb_'.$size] = self::getThumbUrl($this->app_id, $item['set_id'], $item['id'], $size, $item['ext']);
			}
		}
		return $item;
	}
	
	public function deleteItem($item_id)
	{
		if(!$this->id) {return;}
		$item_data = $this->items_model->getById($item_id);
		if(!$item_data) {return;}
		if($item_data['set_id'] != $this->id) {return;}
		$original_path = self::getOriginalPath($this->app_id, $this->id, $item_data['id'], $item_data['ext']);
		if(file_exists($original_path)) {waFiles::delete($original_path);}
		$thumbs_path = self::getThumbsPath($this->app_id, $this->id, $item_data['id']);
		try {waFiles::delete($thumbs_path);}
		catch(Exception $e) {}
		$this->items_model->deleteById($item_id);
		return array('result' => 1, 'message' => 'Запись удалена.');
	}
	
	public function clearItems()
	{
		if(!$this->id) {return;}
		$items = $this->getItems();
		if(!count($items)) {return;}
		
		foreach($items as $item)
		{
			$original_path = self::getOriginalPath($this->app_id, $this->id, $item['id'], $item['ext']);
			if(file_exists($original_path)) {waFiles::delete($original_path);}
			$thumbs_path = self::getThumbsPath($this->app_id, $this->id, $item['id']);
			try
			{
				waFiles::delete($thumbs_path);
			}
			catch(Exception $e) {continue;}
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
			$original_path = self::getOriginalPath($this->app_id, $this->id, $item['id'], $item['ext']);
			$new_path = self::getOriginalPath($this->app_id, $clone_id, $new_item_id, $item['ext']);
			try {waFiles::copy($original_path, $new_path);}
			catch(Exception $e) {$this->items_model->deleteById($new_item_id);}
		}
		
		return $clone;
	}
	
	public function uploadFromPost($vname, $return_size = null)
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
		if($return_size === null) {$return_size = '0x450';}
		$result = array();
		foreach($files as $file)
		{
			if(!$file->uploaded())
			{
				$result[] = array('result' => 0, 'message' => 'Не удалось загрузить файл. Проверьте лимиты на размер файла (MAX_UPLOAD_FILESIZE)');
				continue;
			}
			try
			{
				$image = $file->waImage();
				$data = array(
					'set_id' => $this->id,
					'name' => '',
					'description' => '',
					'width' => $image->width,
					'height' => $image->height,
					'size' => $file->size,
					'filename' => '',
					'original_filename' => basename($file->name),
					'ext' => $file->extension,
				);
				$image_id = $this->addItem($data);
				$path = self::getOriginalPath($this->app_id, $this->id, $image_id, $file->extension);
				try {
					if((file_exists($path) && !is_writable($path)) || (!file_exists($path) && !waFiles::create($path))) 
					{
						$this->items_model->deleteById($image_id);
						$result[] = array('result' => 0, 'message' => 'Ошибка записи файла. Проверьте права на запись.');
					}
					else
					{
						$filename = $image_id.'.'.$file->extension;
						$image->save($path);
						$this->items_model->updateById($image_id, array('filename' => $filename));
						
						$hook_data = array(
							'set' => &$this,
							'image_id' => $image_id,
						);
						wa($this->app_id)->event('set_image_upload', $hook_data);
						
						$thumb_url = self::getThumbUrl($this->app_id, $this->id, $image_id, $return_size, $file->extension);
						$result[] = array('result' => 1, 'message' => 'Изображение загружено', 'image_id' => $image_id, 'thumb' => $thumb_url);
					}
				}
				catch (Exception $e)
				{
					$this->items_model->deleteById($image_id);
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
	
	public function uploadFromUrl($url, $return_size = null)
	{
		if($return_size === null) {$return_size = '0x450';}
		
		$original_name = explode('/', $url);
		$original_name = trim($original_name[count($original_name)-1]);
		if(mb_strpos(mb_strtolower($original_name), '.php') !== false) {return array('result' => 0, 'message' => 'Что-то не так с расширением файла');}
		if(mb_strpos(mb_strtolower($original_name), '.phtml') !== false) {return array('result' => 0, 'message' => 'Что-то не так с расширением файла');}
		
		$upload_path = wa()->getDataPath('temp/', false, self::APP_ID).$original_name;
		try {waFiles::upload($url, $upload_path);}
		catch (Exception $e) {return array('result' => 0, 'message' => $e->getMessage());}
		if(!file_exists($upload_path)) {return array('result' => 0, 'message' => 'Ошибка загрузки файла');}
		
		try
		{
			$image = waImage::factory($upload_path);
			$data = array(
				'set_id' => $this->id,
				'name' => '',
				'description' => '',
				'width' => $image->width,
				'height' => $image->height,
				'size' => filesize($upload_path),
				'filename' => '',
				'original_filename' => $original_name,
				'ext' => $image->getExt(),
			);
			$image_id = $this->addItem($data);
			$path = self::getOriginalPath($this->app_id, $this->id, $image_id, $image->getExt());
			try
			{
				if((file_exists($path) && !is_writable($path)) || (!file_exists($path) && !waFiles::create($path))) 
				{
					$this->items_model->deleteById($image_id);
					waFiles::delete($upload_path);
					return array('result' => 0, 'message' => 'Ошибка записи файла. Проверьте права на запись.');
				}
				else
				{
					$filename = $image_id.'.'.$image->getExt();
					$image->save($path);
					$this->items_model->updateById($image_id, array('filename' => $filename));
					
					$hook_data = array(
						'set' => &$this,
						'image_id' => $image_id,
					);
					wa($this->app_id)->event('set_image_url_upload', $hook_data);
					
					$thumb_url = self::getThumbUrl($this->app_id, $this->id, $image_id, $return_size, $image->getExt());
					waFiles::delete($upload_path);
					return array('result' => 1, 'message' => 'Изображение загружено', 'image_id' => $image_id, 'thumb' => $thumb_url);
				}
			}
			catch(Exception $e)
			{
				$this->items_model->deleteById($image_id);
				waFiles::delete($upload_path);
				return array('result' => 0, 'message' => $e->getMessage());
			}
		}
		catch (Exception $e)
		{
			waFiles::delete($upload_path);
			return array('result' => 0, 'message' => 'Файл не является изображением');
		}
	}
	
	static public function getOriginalPath($app_id, $set_id, $image_id = null, $ext = null)
	{
		$str = str_pad($set_id, 6, '0', STR_PAD_LEFT);
        $path = wa()->getDataPath('images/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), false, $app_id, false);
		if(!$image_id) {return $path;}
		return $path.'/'.$image_id.'.'.$ext;
	}
	
	static public function getThumbsPath($app_id, $set_id, $image_id = null, $size = null, $ext = null)
	{
		$str = str_pad($set_id, 6, '0', STR_PAD_LEFT);
        $path = wa()->getDataPath('images/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), true, $app_id, false);
		if(!$image_id) {return $path;}
		if(!$size || !$ext) {return $path.'/'.$image_id;}
		return $path.'/'.$image_id.'.'.$size.'.'.$ext;
	}
	
	static public function getThumbUrl($app_id, $set_id, $image_id = null, $size = null, $ext = null, $absolute = false)
	{
		$str = str_pad($set_id, 6, '0', STR_PAD_LEFT);
		$url = wa()->getDataUrl('images/'.substr($str, -2).'/'.substr($str, -4, 2).'/'.substr($str, -6, 2), true, $app_id, $absolute);
		if(!$image_id || !$size) {return $url;}
		if(self::isWebpSupported()) {return $url.'/'.$image_id.'/'.$image_id.'.'.$size.'@'.$ext.'.webp';}
		return $url.'/'.$image_id.'/'.$image_id.'.'.$size.'.'.$ext;
	}

	static public function generateThumb($source_path, $size, $max_size, $to_webp = 0)
	{
        $image = waImage::factory($source_path);
		$ext = $image->getExt();
		if($to_webp && self::isWebpConvertableExtension($ext))
		{
			if(!waImage::isWebpSupported()) {throw new waException('Webp is not supported by server configuration');}
			if(!file_exists($source_path)) {throw new waException('No source image provided');}
			$temp_path = wa()->getDataPath('tmp/webp/');
			$ex = explode('/', $source_path);
			$temp_path .= $ex[count($ex)-1].'.webp';
			if($ext == 'jpg' || $ext == 'jpeg') {$temp_image = imagecreatefromjpeg($source_path);}
			elseif($ext == 'png') {$temp_image = imagecreatefrompng($source_path); imagepalettetotruecolor($temp_image);}
			else {throw new waException('Can not convert this format to Webp');}
			
			try {
				
				imagewebp($temp_image, $temp_path, 80);
				$image = waImage::factory($temp_path);
				waFiles::delete($temp_path);
				imagedestroy($temp_image);
			}
			catch(Exception $e) {
				throw new waException('Webp conversion failed');
			}
		}
		
        $width = $height = null;
        $size_info = self::parseSize($size);
        $type = $size_info['type'];
        $width = $size_info['width'];
        $height = $size_info['height'];

        switch($type)
		{
            case 'max':
                if (is_numeric($max_size) && $width > $max_size) {
                    return null;
                }
                $image->resize($width, $height);
                break;
            case 'width':
                if (is_numeric($max_size) && ($width > $max_size || $height > $max_size)) {
                    return null;
                }
                $image->resize($width, $height);
                break;
            case 'height':
                if (is_numeric($max_size) && ($width > $max_size || $height > $max_size)) {
                    return null;
                }
                $image->resize($width, $height);
                break;
            case 'crop':
            case 'rectangle':
                if (is_numeric($max_size) && ($width > $max_size || $height > $max_size)) {
                    return null;
                }
                $image->resize($width, $height, waImage::INVERSE)->crop($width, $height);
                break;
            default:
                throw new waException('Ошибка определения размеров изображения');
                break;
        }

        return $image;
	}
	
	public static function parseSize($size)
    {
        $type = 'unknown';
        $ar_size = explode('x', $size);
        $width = !empty($ar_size[0]) ? $ar_size[0] : null;
        $height = !empty($ar_size[1]) ? $ar_size[1] : null;

        if (count($ar_size) == 1)
		{
            $type = 'max';
            $height = $width;
        } 
		else 
		{
            if($width == $height)
			{
                $type = 'crop';
            }
			else 
			{
                if ($width && $height)
				{
                    $type = 'rectangle';
                }
				else
				{
                    if (is_null($width)) 
					{
                        $type = 'height';
                    }
					else
					{
						if (is_null($height))
						{
                           $type = 'width';
                        }
					}
				}
            }
        }
        return array(
            'type'   => $type,
            'width'  => $width,
            'height' => $height
        );
    }
	
	static public function webpConvertableExtensions()
	{
		return array(
			'jpg' => true,
			'jpeg' => true,
			'png' => true,
		);
	}
	
	static public function isWebpConvertableExtension($ext)
	{
		$exts = self::webpConvertableExtensions();
		if(!empty($exts[$ext])) {return 1;}
		return 0;
	}

	static public function isWebpSupported()
	{
		if(waRequest::param(self::APP_ID.'_disable_webp', 0, 'int')) {return 0;}
		// Очень хотели использовать waImage::isWebpSupported(), но оно постоянно генерирует warning, было неудобно, сделали свое.
		if(!isset($_SERVER['HTTP_ACCEPT'])) {return 0;}
		return(strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') >= 0);
	}
}