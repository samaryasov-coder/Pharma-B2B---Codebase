<?php

class waproSetsInstall
{
	protected $app_id = null;
	
	public function __construct()
	{
		$this->app_id = wa()->getApp();
	}
	
	public function install()
	{
		$installer_path = dirname(__FILE__).'/db.php';
		$tables = include($installer_path);
		$app_tables = array();
		foreach($tables as $key => $table)
		{
			$app_tables[$this->app_id.'_'.$key] = $table;
		}
		$model = new waModel();
        $model->createSchema($app_tables);
		
		$thumb_public_php = file_get_contents(dirname(__FILE__).'/thumb_public.php');
		$thumb_public_php = str_replace('%CURRENT_APP_ID%', $this->app_id, $thumb_public_php);
		
		$data_path = wa()->getDataPath('images', true, $this->app_id);
		waFiles::create($data_path);
		waFiles::create($data_path.'/thumb.php');
		file_put_contents($data_path.'/thumb.php', $thumb_public_php);
		waFiles::copy(dirname(__FILE__).'/.htaccess', $data_path.'/.htaccess');
		
		$app_path = wa()->getAppPath('lib/config/data', $this->app_id);
		if(!is_dir($app_path) && !file_exists($app_path)) waFiles::create($app_path);
		$thumb_php = file_get_contents(dirname(__FILE__).'/thumb.php');
		$thumb_php = str_replace('%CURRENT_APP_ID%', $this->app_id, $thumb_php);
		file_put_contents($app_path.'/thumb.php', $thumb_php);
		
		return array('result' => 1, 'message' => 'Установлено');
	}
}