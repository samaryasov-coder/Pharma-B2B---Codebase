<?php

class pb2bSettingsAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        // $collection = new pb2bDocflowTemplateCollection('itemsWithFiles.id=14&itemsWithFiles.item_company_type_id=1&process_type=1');
        // $rows = $collection->getCollection(array(
        //     'key' => false,
        // )); 
        // wa_dump($rows);

        // $search = 'Тест';

        // $collection = new pb2bDocflowTemplateCollection(
        //     "items.company_id=7&items.process_type=1&items.name~={$search}"
        // );

        // $rows = $collection->getCollection([
        //     'key' => false,
        //     'select' => [
        //         'id' => null,
        //         'company_id' => null,
        //         'file_set_id' => null,
        //         'process_type' => null,
        //         'refresh_period_days' => null,
        //         ['field' => 'id', 'table' => 'DTI', 'as' => 'item_id'],
        //         ['field' => 'name', 'table' => 'DTI', 'as' => 'text'],
        //         ['field' => 'comment', 'table' => 'DTI', 'as' => 'item_comment'],
        //         ['field' => 'file_id', 'table' => 'DTI', 'as' => 'item_file_id'],
        //         ['field' => 'sort', 'table' => 'DTI', 'as' => 'item_sort'],
        //         ['field' => 'company_type_id', 'table' => 'DTI', 'as' => 'item_company_type_id'],
        //     ],
        //     'order' => [
        //         'name' => ['table' => 'DTI', 'dir' => 'ASC'],
        //     ],
        // ]);

        // wa_dump($rows);
        $this->view->assign(array(
            'settings' => (array) pb2bWaproHelper::getConfigOption('settings'),
        ));
    }
}
