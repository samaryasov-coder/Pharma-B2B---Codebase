<?php

class pb2bFrontendCabinetTenderAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0 || !$company || !$company->id) {
            throw new waException('Тендер не найден', pb2bHttpStatus::BAD_REQUEST);
        }

        $tender = (new pb2bTenderService())->getFromBuyer($tender_id, (int) $company->id);
        $extra = (new pb2bTenderCollection())->getWithClassifiers((int) $tender->id);

        $items = $tender->getItemsForView();
        $tech_specs = $tender->getDocumentsForView(pb2bTenderDocument::KIND_TECH_SPEC);
        $requirement_docs = $tender->getDocumentsForView(pb2bTenderDocument::KIND_REQUIREMENT);
        $items_max_total = $tender->getItemsMaxTotal();

        $this->view->assign([
            'tender' => $tender,
            'card' => pb2bTenderResource::make($tender)->resolve(),
            'criteria' => (array) ($extra['criteria'] ?? []),
            'invitations' => (array) ($extra['invitations'] ?? []),
            'classifiers' => (array) ($extra['classifiers'] ?? []),
            'items' => $items,
            'tech_specs' => $tech_specs,
            'requirement_docs' => $requirement_docs,
            'items_max_total' => $items_max_total,
            'organizer' => $company,
        ]);
        $this->setThemeTemplate('html/cabinet/buyer/tender.html');
    }

    public function executeSupplier()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0 || !$company || !$company->id) {
            throw new waException('Тендер не найден', pb2bHttpStatus::BAD_REQUEST);
        }

        $notice = (new pb2bTenderApplicationService())->getNotice($tender_id, (int) $company->id);
        $card = (array) ($notice['tender'] ?? []);
        $resolved_id = (int) ($card['id'] ?? $tender_id);
        $extra = (new pb2bTenderCollection())->getWithClassifiers($resolved_id);

        $organizer = null;
        $tender = new pb2bTender($resolved_id);
        $organizer_id = (int) ($tender->data['organizer_company_id'] ?? 0);
        if ($organizer_id > 0) {
            $organizer = new pb2bCompany($organizer_id);
            if (!(int) $organizer->id) {
                $organizer = null;
            }
        }

        $application = $notice['application'] ?? null;
        $app_prices = array();
        $app_criteria = array();
        $app_documents = array();
        if (is_array($application)) {
            foreach ((array) ($application['items'] ?? array()) as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $tid = (int) ($row['tender_item_id'] ?? 0);
                if ($tid > 0) {
                    $app_prices[$tid] = $row['price_per_unit'] ?? null;
                }
            }
            foreach ((array) ($application['criteria'] ?? array()) as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $cid = (int) ($row['criterion_id'] ?? 0);
                if ($cid > 0) {
                    $app_criteria[$cid] = $row;
                }
            }
            foreach ((array) ($application['documents'] ?? array()) as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $did = (int) ($row['tender_document_id'] ?? 0);
                if ($did > 0) {
                    $app_documents[$did] = $row;
                }
            }
        }

        $this->view->assign([
            'tender_id' => $resolved_id,
            'card' => $card,
            'items' => (array) ($notice['items'] ?? []),
            'documents' => (array) ($notice['documents'] ?? []),
            'criteria' => (array) ($notice['criteria'] ?? []),
            'application' => $application,
            'app_prices' => $app_prices,
            'app_criteria' => $app_criteria,
            'app_documents' => $app_documents,
            'gates' => (array) ($notice['gates'] ?? []),
            'classifiers' => (array) ($extra['classifiers'] ?? []),
            'organizer' => $organizer,
            'supplier' => $company,
        ]);
        $this->setThemeTemplate('html/cabinet/supplier/tender.html');
    }
}
