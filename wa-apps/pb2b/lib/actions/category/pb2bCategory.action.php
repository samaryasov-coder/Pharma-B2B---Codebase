<?php

class pb2bCategoryAction extends pb2bWaproViewAction
{
    /**
     * @return void
     * @throws waDbException
     * @throws waException
     */
    public function execute(): void
    {
        $category = new pb2bCategory();
        $tree = $category->getTree();
        $this->view->assign(array(
            'categories_tree' => $tree->getTree(),
            'categories' => $tree->basic_tree,
        ));
    }
}