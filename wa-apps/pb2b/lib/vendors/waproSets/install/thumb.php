<?php
	$app_id = 'pb2b';
	$path = realpath(dirname(__FILE__)."/../../../../../");
	$config_path = $path."/wa-config/SystemConfig.class.php";
	if(!file_exists($config_path)) {header("Location: ../../../wa-apps/".$app_id."/img/no-image.png"); exit;}
	require_once($config_path);
	$config = new SystemConfig();
	waSystem::getInstance(null, $config);
	$app_config = wa($app_id)->getConfig();
	$request_file = $app_config->getRequestUrl(true, true);
	$request_file = preg_replace("@^thumb.php(/images)?/?@", '', $request_file);
	$protected_path = wa()->getDataPath('images/', false, $app_id);
	$public_path = wa()->getDataPath('images/', true, $app_id);
	$file = false;
	$size = false;
	$to_webp = 0;
	$enable_2x = false;
	if(preg_match('#([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)/([a-zA-Z0-9_\.-]+)\.(\d+(?:x\d+)?)(@2x)?(@[a-z]{3,4})?\.([a-z]{3,4})#i', $request_file, $matches))
	{
		$size = $matches[6];
		if(!empty($matches[8]))
		{
			$matches[8] = mb_substr($matches[8], 1);
			if($matches[9] == 'webp') {$to_webp = 1;}
			$file = $matches[1].'/'.$matches[2].'/'.$matches[3].'/'.$matches[5].'.'.$matches[8];
		}
		else
		{
			$file = $matches[1].'/'.$matches[2].'/'.$matches[3].'/'.$matches[5].'.'.$matches[9];
		}
	}
	wa()->getStorage()->close();

	$original_path = $protected_path.$file;
	$thumb_path = $public_path.$request_file;
	if($file && file_exists($original_path) && !file_exists($thumb_path))
	{
		$thumbs_dir = dirname($thumb_path);
		if(!file_exists($thumbs_dir))
		{
			waFiles::create($thumbs_dir);
		}
		$max_size = '2000';
		$image = waproBagginsImageSet::generateThumb($original_path, $size, $max_size, $to_webp);
		if($image)
		{
			$image->save($thumb_path, 100);
			clearstatcache();
		}
	}
	if($file && file_exists($thumb_path))
	{
		waFiles::readFile($thumb_path);
	}
	else
	{
		header("HTTP/1.0 404 Not Found");
		exit;
	}
