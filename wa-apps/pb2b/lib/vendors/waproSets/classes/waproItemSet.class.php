<?php
class waproItemSet
{
	const APP_ID = 'pb2b';
	
	protected $id = null;
	protected $model = null;
	protected $items_model = null;
	protected $app_id = null;
	
	public function __construct($id = null)
    {
		$this->app_id = 'pb2b'; //wa()->getApp();
		$this->id = $id;
		if(!$this->model) {$this->model = new waproItemSetModel();}
		if(!$this->items_model) {$this->items_model = new waproItemSetItemsModel();}
		if($this->id === null) {$this->id = $this->model->insert(array('create_datetime' => date('Y-m-d H:i:s')));}
		else
		{
			if(!$this->model->getById($this->id)) {$this->id = 0;}
		}
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function addItems($items)
	{
		if(!$this->id) {return;}
		if(!count($items)) {return;}
		
		foreach($items as $key => $item)
		{
			$this->items_model->addItem($this->id, $item);
		}
	}
	
	public function addItem($item)
	{
		if(!$this->id) {return null;}
		return $this->items_model->addItem($this->id, $item);
	}
	
	public function getItems($raw = false, $start = 0, $limit = null)
	{
		if(!$this->id) {return array();}
		$items = $this->items_model->getBySet($this->id, $start, $limit);
		if(!count($items)) {return array();}
		if($raw) {return $items;}
		
		foreach($items as $key => $item)
		{
			if($item['extra'] !== null)
			{
				try {$items[$key]['extra'] = json_decode($item['extra'], true);}
				catch(Exception $e) {$items[$key]['extra'] = array();}
			}
			else {$items[$key]['extra'] = array();}
		}
		
		return $items;
	}
	
	public function getItem($item_id, $raw = false)
	{
		if(!$this->id) {return null;}
		$item = $this->items_model->getById($item_id);
		if(!$item) {return null;}
		if($item['set_id'] != $this->id) {return null;}
		if($raw) {return $item;}
		
		if($item['extra'] !== null) 
		{
			try {$item['extra'] = json_decode($item['extra'], true);}
			catch(Exception $e) {$item['extra'] = array();}
		}
		else {$item['extra'] = array();}
		
		return $item;
	}
	
	public function getItemByField($field, $value, $raw = false)
	{
		if(!$this->id) {return null;}
		$item = $this->items_model->getOneByField($field, $value, $this->id);
		
		if(!$item) {return null;}
		if($item['set_id'] != $this->id) {return null;}
		if($raw) {return $item;}
		
		if($item['extra'] !== null)
		{
			try {$item['extra'] = json_decode($item['extra'], true);}
			catch(Exception $e) {$item['extra'] = array();}
		}
		else {$item['extra'] = array();}
		
		return $item;
	}
	
	public function getFirstItem()
	{
		if(!$this->id) {return null;}
		$items = $this->getItems();
		if(!count($items)) {return null;}
		foreach($items as $item) {return $item;}
	}
	
	public function getItemsCount()
	{
		if(!$this->id) {return array();}
		return $this->items_model->countBySet($this->id);
	}
	
	public function updateItem($item_id, $data)
	{
		if(!$this->id) {return array('result' => 0, 'message' => 'Набор не существует');}
		$item = $this->getItem($item_id);
		if(!$item) {return array('result' => 0, 'message' => 'Объект не найден');}
		if(!is_array($data)) {return;}
		
		if(count($item['extra']))
		{
			foreach($item['extra'] as $key => $value)
			{
				if(!isset($item[$key])) {$item[$key] = $value;}
			}
		}
		unset($item['extra']);
		
		if(array_key_exists('id', $data)) {unset($data['id']);}
		if(array_key_exists('set_id', $data)) {unset($data['set_id']);}
		if(array_key_exists('sort', $data)) {unset($data['sort']);}
		if(array_key_exists('extra', $data)) {unset($data['extra']);}
		if(!count($data)) {return array('result' => 0, 'message' => 'Ошибка обработки данных');}
		
		foreach($data as $key => $value)
		{
			$item[$key] = $value;
		}
		unset($item['id']);
		
		$this->items_model->updateItem($item_id, $item);
		
		return array('result' => 1, 'message' => 'Данные обновлены', 'item' => $this->getItem($item_id));
	}
	
	public function deleteItem($item_id)
	{
		if(!$this->id) {return;}
		$item_data = $this->items_model->getById($item_id);
		if(!$item_data) {return;}
		if($item_data['set_id'] != $this->id) {return;}
		$this->items_model->deleteById($item_id);
	}
	
	public function sortItems($items)
	{
		if(!$this->id) {return;}
		if(!count($items)) {return;}
		$self_items = $this->getItems();
		
		foreach($items as $key => $item)
		{
			$exists = 0;
			foreach($self_items as $s_item)
			{
				if($item == $s_item['id']) {$exists = 1;}
			}
			if(!$exists) {unset($items[$key]);}
		}
		if(!count($items)) {return;}
		
		$counter = 1;
		foreach($items as $item)
		{
			$this->items_model->updateById($item, array('sort' => $counter));
			$counter++;
		}
	}
	
	public function clearItems()
	{
		if(!$this->id) {return;}
		$this->items_model->clearBySet($this->id);
	}
	
	public function clear()
	{
		if(!$this->id) {return;}
		$this->clearItems();
		$this->model->deleteById($this->id);
		$this->id = 0;
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
			$this->items_model->addRawItem($clone_id, $item);
		}
		
		return $clone;
	}
}