<?php
class pb2bFrontendCabinetDataAction extends pb2bFrontendCabinetAction
{
    public function executeAction(){
        $this->setThemeTemplate('html/cabinet/data.html');
        $this->view->assign('company', $this->context->company());
    }
}