<?php

class pb2bDocflowTemplateDefaultAddAction extends pb2bWaproViewAction
{
    public function execute(): void
    {
        $this->view->assign(array(
            'company_types' => (array) pb2bWaproHelper::getConfigOption('company_type'),
        ));
    }
}
