<?php

class pb2bFrontendCabinetPrequalEditAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer(): void
    {
        $id = waRequest::param('id', 0, waRequest::TYPE_INT);
        $prequal = (new pb2bPrequalService())->getFromBuyer($id, $this->context->company());
        if (!$prequal->canEdit()) {
            throw new waException('Изменять можно только черновик ПК', pb2bHttpStatus::CONFLICT);
        }
        $this->view->assign(array(
            'prequal_id' => (int) $prequal->id,
            'prequal_data' => $prequal->data,
            'prequal_invitations' => $prequal->getInvitations(),
        ));
        $this->setCabinetThemeTemplate('prequal/form.html');
    }
}
