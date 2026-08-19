<?php

class pb2bCompanyCategorySelectAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        $object = new pb2bCompany(waRequest::get('id', null, waRequest::TYPE_INT));
        $data['object'] = $object->data;
        
        if($object->id) {
            $service = new pb2bCompanyCategoryService();
            $data['selected_category_ids'] = $service->getSelectedIds($object->id);
        }
        
        $category = new pb2bCategory();
        $tree = $category->getTree();
        $data['categories_tree'] = $tree->getTree();
        $this->view->assign($data);
    }
}