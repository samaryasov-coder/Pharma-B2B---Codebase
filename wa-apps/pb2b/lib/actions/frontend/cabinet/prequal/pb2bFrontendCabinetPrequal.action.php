<?php

class pb2bFrontendCabinetPrequalAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer(): void
    {
        $overview = (new pb2bPrequalService())->getBuyerOverview(waRequest::param('id', 0, waRequest::TYPE_INT), $this->context->company());
        $prequal = $overview['prequal'];
        $this->view->assign($overview);
        $this->view->assign(array(
            'prequal' => $prequal,
            'organizer' => $this->context->company(),
            'classifiers' => $prequal->getClassifiers(),
            'items' => $prequal->getItems(),
            'documents' => $prequal->getDocuments(),
            'tech_spec_kind' => pb2bPrequalDocument::KIND_TECH_SPEC,
            'requirement_kind' => pb2bPrequalDocument::KIND_REQUIREMENT,
            'prequal_status' => (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'id'),
            'application_statuses' => (array) pb2bWaproHelper::getConfigOption('prequal_application_statuses', 'id'),
            'decision_types' => (array) pb2bWaproHelper::getConfigOption('prequal_decision_types', 'id'),
            'tabs' => pb2bTenderPageTab::buyerPrequal(),
        ));
        $this->setCabinetThemeTemplate('prequal/prequal.html');
    }
    public function executeSupplier(): void
    {
        $prequal = (new pb2bPrequalService())->getFromSupplier(waRequest::param('id', 0, waRequest::TYPE_INT), $this->context->company());
        $organizer = new pb2bCompany((int) $prequal->data['reviewer_id']);
        $statuses = (array) pb2bWaproHelper::getConfigOption('prequal_statuses', 'id');
        $this->view->assign(array(
            'prequal' => $prequal,
            'organizer' => $organizer,
            'can_apply' => $prequal->canAcceptApplications(),
            'own_application' => $prequal->getApplicationBySupplier((int) $this->context->company()->id),
            'classifiers' => $prequal->getClassifiers(),
            'items' => $prequal->getItems(),
            'documents' => $prequal->getDocuments(),
            'tech_spec_kind' => pb2bPrequalDocument::KIND_TECH_SPEC,
            'requirement_kind' => pb2bPrequalDocument::KIND_REQUIREMENT,
            'prequal_status' => $statuses[(int) $prequal->data['status']] ?? array(),
            'deadline' => pb2bHelper::resolveTenderDeadline((string) ($prequal->data['accept_end_datetime'] ?? ''), false),
            'tabs' => array(
                pb2bTenderPageTab::NOTICE->toArray(true),
                pb2bTenderPageTab::POSITIONS->toArray(),
                pb2bTenderPageTab::DOCUMENTS->toArray(),
            ),
        ));
        $this->setCabinetThemeTemplate('prequal.html');
    }
}
