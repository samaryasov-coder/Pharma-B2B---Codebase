<?php
abstract class pb2bWaproService
{
    protected pb2bWaproModel $relationModel;

    public function setFirst(pb2bWaproObject $object, array $item_ids, $main = 0): array
    {
        $result = array('error' => false);
        if(!$object->offsetExists('id')) {
            $result = array('error' => true, 'message' => $object->getNameCase(0).' не существует');
        } else {
            $id = $this->relationModel->getTableId();
            if(is_array($id) && count($id) == 2) {
                $this->relationModel->deleteByField($id[$main], $object->id);
                $data = array();
                $counter = 0;
                foreach($item_ids as $item_id) {
                    $item = array($id[$main] => $object->id, $id[$main == 0 ? 1 : 0] => $item_id);
                    if(isset($this->relationModel->fields['sort'])) {
                        $item['sort'] = $counter++;
                    }
                    $data[] = $item;
                }
                if(!empty($data)) $this->relationModel->multipleInsert($data);
            } else {
                $result = array('error' => true, 'message' => 'У таблицы связи неверная структура');
            }
        }
        return $result;
    }

    public function getSelectedIds(int $item_id, int $main = 0): array
    {
        $id = $this->relationModel->getTableId();
        if(!is_array($id) || count($id) != 2 || !$item_id) return array();

        $main_field = $id[$main];                 
        $child_field = $id[$main == 0 ? 1 : 0];   

        $this->relationModel->setFetch('all');
        $this->relationModel->setSelect(array(
            $child_field => null,
        ));
        $this->relationModel->setWhere(array(
            $main_field => array('simile' => '=', 'value' => $item_id),
        ));

        $rows = $this->relationModel->queryRun();

        $ids = array();
        foreach ($rows as $row) {
            $ids[] = (int) $row[$child_field];
        }
        return $ids;
    }

    // public function getSelectedIds(int $item_id, int $main = 0): array
    // {
    //     $id = $this->relationModel->getTableId();
    //     $ids = array();
    //     if(is_array($id) && count($id) == 2) {
            
    //         $this->relationModel->setFetch('all', $id[$main], 1);
    //         $this->relationModel->setSelect(array($id[$main] => array($id[$main == 0 ? 1 : 0], 'tmp')));
    //         $this->relationModel->setWhere(array($id[$main] => array('simile' => '=', 'value' => $item_id)));
    //         $ids = $this->relationModel->queryRun();
    //     }
    //     return $ids;
    // }

    // public function getSelectedItems(pb2bWaproCollection $collection, array $params = array(), int $main = 0): array
    // {
    //     return $collection->getCollection($params);
    // }
}