<?php
class waproItemSetItemsModel extends waModel
{
    protected $table = null;
	protected $app_id = 'pb2b';
	
	public function __construct($type = null, $writable = false)
    {
		if($this->table === null) {$this->table = $this->app_id.'_wapro_item_set_items';}
        return parent::__construct($type, $writable);
    }
	
	public function getBySet($set_id, $start = 0, $limit = null)
	{
		$offset = "";
		if($limit !== null && $start === null) {$offset = " LIMIT ".intval($limit);}
		elseif($limit !== null && $start !== null) {$offset = " LIMIT ".intval($start).", ".intval($limit);}
		
		return $this->query("SELECT * FROM ".$this->table." WHERE set_id = i:set_id ORDER BY sort ASC".$offset,
								array('set_id' => $set_id))->fetchAll();
	}
	
	public function countBySet($set_id)
	{
		$data = $this->query("SELECT COUNT(*) AS cnt FROM ".$this->table." WHERE set_id = i:set_id ORDER BY sort ASC",
								array('set_id' => $set_id))->fetchAll();
		if(!count($data)) {return 0;}
		return $data[0]['cnt'];
	}
	
	public function clearBySet($set_id)
	{
		$this->query("DELETE FROM ".$this->table." WHERE set_id = i:set_id", array('set_id' => $set_id));
	}
	
	public function getMaxSortBySet($set_id)
	{
		$data = $this->query("SELECT MAX(sort) AS mx FROM ".$this->table." WHERE set_id = i:set_id",
								array('set_id' => $set_id))->fetchAll();
		if(!count($data)) {return 1;}
		return $data[0]['mx']+1;
	}
	
	public function addItem($set_id, $item)
	{
		if(!is_array($item)) {return null;}
		$data = array(
			'name' => '',
			'set_id' => $set_id,
			'sort' => $this->getMaxSortBySet($set_id),
			'extra' => array()
		);
		$allowed_fields = $this->allowedFields();
		foreach($item as $key => $value)
		{
			if(isset($allowed_fields[$key])) {$data[$key] = $value;}
			else {$data['extra'][$key] = $value;}
		}
		
		try {$data['extra'] = json_encode($data['extra']);}
		catch(Exception $e) {$data['extra'] = json_encode(array());}
		
		return $this->insert($data);
	}
	
	public function updateItem($id, $data)
	{
		if(!is_array($data)) {return null;}
		if(array_key_exists('id', $data)) {unset($data['id']);}
		if(array_key_exists('set_id', $data)) {unset($data['set_id']);}
		if(array_key_exists('sort', $data)) {unset($data['sort']);}
		
		$allowed_fields = $this->allowedFields();
		foreach($data as $key => $value)
		{
			if(!isset($allowed_fields[$key]))
			{
				if(!isset($data['extra'])) {$data['extra'] = array();}
				$data['extra'][$key] = $value;
				unset($data[$key]);
			}
		}
		
		if(isset($data['extra']))
		{
			try {$data['extra'] = json_encode($data['extra']);}
			catch(Exception $e) {$data['extra'] = json_encode(array());}
		}
		
		$this->updateById($id, $data);
	}
	
	public function addRawItem($set_id, $item)
	{
		if(array_key_exists('id', $item)) {unset($item['id']);}
		$item['set_id'] = $set_id;
		return $this->insert($item);
	}
	
	public function allowedFields()
	{
		return array(
			'name' => true,
		);
	}

	public function getOneByField($field, $value, $set_id)
	{
		$data = $this->query("SELECT * FROM ".$this->table." WHERE set_id = i:set_id AND ".$this->escape($field)." = s:value",
								array('set_id' => $set_id, 'value' => $value))->fetchAll();
		if(!count($data)) {return null;}
		return $data[0];
	}

	public function getFirstItemsBySets($set_ids, $fetch_by = 'id')
	{
		if(!count($set_ids)) {return array();}
		return $this->query("SELECT * FROM ".$this->table." WHERE set_id IN (i:ids) GROUP BY set_id ORDER BY sort ASC", array('ids' => $set_ids))->fetchAll($fetch_by);
	}
}
