<?php

class pb2bFrontendCabinetPrequalCreateAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer(): void
    {
        $this->view->assign(array(
            'prequal_id' => 0,
            'prequal_data' => array(),
            'prequal_invitations' => array(),
        ));
        $this->setCabinetThemeTemplate('prequal/form.html');
    }
}
