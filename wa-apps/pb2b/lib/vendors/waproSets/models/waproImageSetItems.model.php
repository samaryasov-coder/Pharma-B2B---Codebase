<?php
class waproImageSetItemsModel extends waproItemSetItemsModel
{
    protected $table = null;
	
	public function __construct($type = null, $writable = false)
    {
		if($this->table === null) {$this->table = $this->app_id.'_wapro_image_set_items';}
        return parent::__construct($type, $writable);
    }
	
	public function allowedFields()
	{
		return array(
			'name' => true,
			'set_id' => true,
			'description' => true,
			'width' => true,
			'height' => true,
			'size' => true,
			'filename' => true,
			'original_filename' => true,
			'ext' => true,
		);
	}
}
