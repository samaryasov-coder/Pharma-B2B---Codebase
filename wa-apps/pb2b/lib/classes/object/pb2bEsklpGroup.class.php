<?php

class pb2bEsklpGroup extends pb2bWaproObject
{

    protected function getTabs(): array
    {
        return array(
            'items' => array(
                'common_group' => array('tab' => 'common', 'name' => 'Общее'),
                'esklp' => array('tab' => 'groups', 'name' => 'Классификаторы'),
            ),
            'options' => array(
                'default_tab' => 'common',
            ),
        );
    }
    /**
     * @return array
     * @throws waDbException
     * @throws waException
     */
    protected function getEsklp(): array
    {
        $esklp = new pb2bEsklpCollection('group.group_id='.$this->id);
        return $esklp->getCollection(array('order' => array('name' => array('dir' => 'ASC', 'table' => $esklp->model->getTableName()))));
    }
}