<?php

class pb2bFrontendCabinetPrequalCreateAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer(): void
    {
        $this->view->assign(array(
            'prequal_steps' => pb2bPrequal::getCreateSteps(),
            'prequal_id' => 0,
            'prequal_data' => array(),
            'prequal_invitations' => array(),
            'prequal_category_ids' => array(),
            'prequal_categories' => (new pb2bWaproTree((new pb2bCategoryCollection())->getCabinetTreeRows()))->getTree(),
            'prequal_documents' => array(),
            'prequal_items' => array(),
        ));
        $this->setCabinetThemeTemplate('prequal/prequal-form.html');
    }
}
