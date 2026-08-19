<?php
class waproItemSetModel extends waModel
{
    protected $table = null;
	protected $app_id = 'pb2b';
	
	public function __construct($type = null, $writable = false)
    {
		if($this->table === null) {$this->table = $this->app_id.'_wapro_item_set';}
        return parent::__construct($type, $writable);
    }
}
