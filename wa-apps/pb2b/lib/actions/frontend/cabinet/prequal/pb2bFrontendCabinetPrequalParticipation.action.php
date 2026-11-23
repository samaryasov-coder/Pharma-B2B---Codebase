<?php

class pb2bFrontendCabinetPrequalParticipationAction extends pb2bFrontendCabinetAction
{
    protected function executeSupplier(): void
    {
        $company = $this->context->company();
        $prequal = (new pb2bPrequalService())->getFromSupplier(waRequest::param('id', 0, waRequest::TYPE_INT), $company);

        //если заявка уже подана редирект нужно как-то потом по уму сделать
        $notice_url = '/cabinet/supplier/prequal/' . $prequal->id . '/';
        if ($prequal->getApplicationBySupplier((int) $company->id)) {
            wa()->getResponse()->redirect($notice_url);
            return;
        }

        $can_apply = $prequal->canAcceptApplications();
        $this->view->assign(array(
            'prequal' => $prequal,
            'organizer' => new pb2bCompany((int) $prequal->data['reviewer_id']),
            'notice_url' => $notice_url,
            'can_apply' => $can_apply,
            'categories' => array_filter(array_column($prequal->getClassifiers(), 'category_name')),
            'technical_documents' => $prequal->getDocuments(pb2bPrequalDocument::KIND_TECH_SPEC),
            'items' => $prequal->getItems(),
            'documents' => $prequal->getDocuments(pb2bPrequalDocument::KIND_REQUIREMENT),
        ));
        $this->setCabinetThemeTemplate('prequal-participation.html');
    }
}
