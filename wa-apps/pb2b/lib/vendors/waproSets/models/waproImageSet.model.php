<?php
class waproImageSetModel extends waproItemSetModel
{
    protected $table = null;
	
	public function __construct($type = null, $writable = false)
    {
		if($this->table === null) {$this->table = $this->app_id.'_wapro_image_set';}
        return parent::__construct($type, $writable);
    }
}
