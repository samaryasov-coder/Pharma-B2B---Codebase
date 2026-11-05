<?php

class pb2bTenderSupplierApplicationPageService
{
    public function getSupplierPageData(int $tender_id, pb2bCompany $company): array
    {
        $service = new pb2bTenderApplicationService();
        $notice = $service->getNotice($tender_id, (int) $company->id, false);
        $application = $notice['application'];
        if (!$application) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }

        $tender = new pb2bTender($tender_id);
        $application_object = new pb2bTenderApplication((int) $application['id']);
        if (!pb2bTenderApplicationPolicy::view($application_object, $company)) {
            throw new waException('Заявка не найдена', pb2bHttpStatus::NOT_FOUND);
        }
        $organizer = new pb2bCompany((int) ($tender->data['organizer_company_id'] ?? 0));
        $delivery_places = array();
        foreach ($notice['items'] as $item) {
            $place = trim((string) ($item['delivery_place'] ?? ''));
            if ($place !== '') {
                $delivery_places[$place] = $place;
            }
        }
        $is_draft = $application_object->getStatusCode() === pb2bTenderApplication::STATUS_DRAFT;
        $status = $application_object->getStatusCode() === pb2bTenderApplication::STATUS_SUBMITTED
            ? $application['admission_status']
            : $application['status'];
        $tender_url = '/cabinet/supplier/tender/' . $tender_id . '/';

        return array(
            'card' => $notice['tender'],
            'application' => $application,
            'positions' => $this->preparePositions($notice['items'], $application['items']),
            'requested_documents' => $this->prepareRequestedDocuments(
                $notice['documents'],
                $application['documents']
            ),
            'non_price_criteria' => $this->prepareNonPriceCriteria(
                $notice['criteria'],
                $application['criteria']
            ),
            'organizer' => $organizer->id ? $organizer : null,
            'delivery_places' => array_values($delivery_places),
            'application_status' => $status,
            'actions' => array(
                'tender_url' => $tender_url,
                'edit_url' => $tender_url . 'participation/' . ($is_draft ? '' : '?step=proposal'),
                'edit_label' => $is_draft ? 'Продолжить заполнение' : 'Редактировать',
                'can_edit' => $service->canEdit($application_object, $tender, $company),
                'can_withdraw' => $service->canWithdraw($application_object, $tender, $company),
            ),
            'tabs' => pb2bTenderPageTab::supplierApplication(),
        );
    }

    private function prepareRequestedDocuments(array $notice_documents, array $application_documents): array
    {
        $application_documents = array_column($application_documents, null, 'tender_document_id');
        $documents = array();
        foreach ($notice_documents as $document) {
            if (($document['kind'] ?? '') !== 'requirement') {
                continue;
            }
            $answer = $application_documents[$document['id']] ?? array();
            $file_link_id = (int) ($answer['file_link_id'] ?? 0);
            $file_name = '';
            if ($file_link_id > 0) {
                $file = new pb2bFileLink($file_link_id);
                $file_name = (string) ($file->data['filename'] ?? '');
            }
            $documents[] = array(
                'name' => (string) ($document['name'] ?? ''),
                'description' => (string) ($document['description'] ?? ''),
                'is_required' => !empty($document['is_required']),
                'file_name' => $file_name,
            );
        }

        return $documents;
    }

    private function prepareNonPriceCriteria(array $notice_criteria, array $application_criteria): array
    {
        $application_criteria = array_column($application_criteria, null, 'criterion_id');
        $criteria = array();
        foreach ($notice_criteria as $criterion) {
            $answer = $application_criteria[$criterion['id']] ?? array();
            $criteria[] = array(
                'id' => (int) ($criterion['id'] ?? 0),
                'name' => (string) ($criterion['name'] ?? ''),
                'description' => (string) ($criterion['description'] ?? ''),
                'is_mandatory' => !empty($criterion['is_mandatory']),
                'type' => (string) ($criterion['type'] ?? 'text'),
                'value' => (string) ($answer['value'] ?? ''),
                'confirmed' => !empty($answer['confirmed']),
                'file_link_id' => (int) ($answer['file_link_id'] ?? 0),
                'file_name' => (string) ($answer['file_name'] ?? ''),
            );
        }

        return $criteria;
    }

    private function preparePositions(array $notice_items, array $application_items): array
    {
        $notice_items = array_column($notice_items, null, 'id');
        usort($application_items, static fn(array $a, array $b): int =>
            [(int) $a['sort'], (int) $a['id']] <=> [(int) $b['sort'], (int) $b['id']]
        );
        $items = array();
        $net = 0;
        $vat = 0;
        $prices_complete = true;
        $vat_complete = true;
        foreach ($application_items as $item) {
            $notice_item = $notice_items[$item['tender_item_id']] ?? array();
            $amount = $item['amount'];
            $vat_label = trim((string) ($item['vat_rate'] ?? ''));
            if (is_numeric(str_replace(',', '.', $vat_label))) {
                $vat_label .= '%';
            }
            $rate = $this->vatRate($vat_label);
            if ($amount === null) {
                $prices_complete = false;
            } else {
                $net += (int) round((float) $amount * 100);
                if ($rate !== null) {
                    $vat += (int) round((float) $amount * $rate);
                }
            }
            if ($rate === null) {
                $vat_complete = false;
            }
            $items[] = array(
                'name' => (string) ($notice_item['name'] ?? ''),
                'qty' => $item['qty'],
                'unit' => $item['unit'],
                'vat_rate' => $vat_label,
                'price_per_unit' => $item['price_per_unit'],
            );
        }

        return array(
            'items' => $items,
            'totals' => array(
                'net' => $prices_complete ? $net / 100 : null,
                'vat' => $prices_complete && $vat_complete ? $vat / 100 : null,
                'gross' => $prices_complete && $vat_complete ? ($net + $vat) / 100 : null,
                'prices_complete' => $prices_complete,
                'vat_complete' => $vat_complete,
            ),
        );
    }

    private function vatRate(string $value): ?float
    {
        $value = mb_strtolower(trim($value));
        if (in_array($value, array('без ндс', 'безндс', 'no_vat', 'without_vat', 'none'), true)) {
            return 0.0;
        }
        if (preg_match('/^(\d+(?:[.,]\d+)?)\s*%?$/D', $value, $matches)) {
            $rate = (float) str_replace(',', '.', $matches[1]);
            return $rate <= 100 ? $rate : null;
        }

        return null;
    }

}
