<?php

/**
 * Wizard участия поставщика: cabinet/supplier/tender/<id>/participation/
 */
class pb2bFrontendCabinetTenderParticipationAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        throw new waException('Страница только для поставщика', pb2bHttpStatus::FORBIDDEN);
    }

    public function executeSupplier()
    {
        $company = $this->context->company();
        $tender_id = waRequest::param('id', 0, waRequest::TYPE_INT);
        if ($tender_id <= 0 || !$company || !$company->id) {
            throw new waException('Тендер не найден', pb2bHttpStatus::BAD_REQUEST);
        }

        $service = new pb2bTenderApplicationService();
        $company_id = (int) $company->id;

        try {
            $service->getOrCreateDraft($tender_id, $company_id);
        } catch (waException $e) {
            // withdrawn / приём закрыт — getNotice ниже.
        }

        $notice = $service->getNotice($tender_id, $company_id);
        $card = (array) ($notice['tender'] ?? []);
        $resolved_id = (int) ($card['id'] ?? $tender_id);
        $application = $notice['application'] ?? null;
        $app_status_code = '';
        if (is_array($application) && is_array($application['status'] ?? null)) {
            $app_status_code = (string) ($application['status']['code'] ?? '');
        }

        $requested_step = waRequest::get('step', '', waRequest::TYPE_STRING_TRIM);
        $tender = new pb2bTender($resolved_id);
        $application_obj = null;
        if (is_array($application) && (int) ($application['id'] ?? 0) > 0) {
            $application_obj = new pb2bTenderApplication((int) $application['id']);
            if (!(int) $application_obj->id) {
                $application_obj = null;
            }
        }

        $flow = $service->resolveParticipationFlow($application_obj, $tender, $requested_step);
        $participation_url = '/cabinet/supplier/tender/' . $resolved_id . '/participation/';
        $notice_url = '/cabinet/supplier/tender/' . $resolved_id . '/';

        // Locked step в URL → редирект на первый доступный (next_required).
        if (!empty($flow['redirected'])) {
            $target = $participation_url . '?step=' . rawurlencode((string) $flow['current_step']);
            wa()->getResponse()->redirect($target);
            return;
        }

        $current_step = pb2bTenderParticipationStep::tryParse((string) ($flow['current_step'] ?? ''))
            ?? pb2bTenderParticipationStep::NON_PRICE;
        $next_step = pb2bTenderParticipationStep::tryParse((string) ($flow['next_required_step'] ?? ''))
            ?? pb2bTenderParticipationStep::NON_PRICE;
        $current = $current_step->value;
        $next = $next_step->value;
        $next_accessible = false;
        foreach ((array) ($flow['stages'] ?? array()) as $stage) {
            if (!is_array($stage) || (string) ($stage['id'] ?? '') !== $next) {
                continue;
            }
            $next_accessible = !empty($stage['accessible']);
            break;
        }
        $can_go_next = ($next !== $current) && $next_accessible;
        $gates = (array) ($flow['gates'] ?? ($notice['gates'] ?? array()));
        $participation_stages = (array) ($flow['stages'] ?? array());
        $stages_total = count($participation_stages);
        $step_number = 1;
        foreach ($participation_stages as $i => $stage) {
            if (!is_array($stage)) {
                continue;
            }
            if ((string) ($stage['id'] ?? '') === $current) {
                $step_number = $i + 1;
                break;
            }
        }
        $app_criteria = array();
        if (is_array($application)) {
            foreach ((array) ($application['criteria'] ?? array()) as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $cid = (int) ($row['criterion_id'] ?? 0);
                if ($cid > 0) {
                    $app_criteria[$cid] = $row;
                }
            }
        }
        $can_edit_nonprice = $app_status_code !== 'withdrawn';
        $can_edit_proposal = $app_status_code !== 'withdrawn';

        $app_prices = array();
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

        $proposal_sub = waRequest::get('sub', 'lots', waRequest::TYPE_STRING_TRIM);
        if (!in_array($proposal_sub, array('lots', 'documents'), true)) {
            $proposal_sub = 'lots';
        }
        if ($current !== pb2bTenderParticipationStep::PROPOSAL->value) {
            $proposal_sub = 'lots';
        }

        $this->view->assign(array(
            'tender_id' => $resolved_id,
            'card' => $card,
            'items' => (array) ($notice['items'] ?? array()),
            'documents' => (array) ($notice['documents'] ?? array()),
            'criteria' => (array) ($notice['criteria'] ?? array()),
            'app_criteria' => $app_criteria,
            'app_prices' => $app_prices,
            'app_documents' => $app_documents,
            'application' => $application,
            'gates' => $gates,
            'app_status_code' => $app_status_code,
            'can_edit_nonprice' => $can_edit_nonprice,
            'can_edit_proposal' => $can_edit_proposal,
            'proposal_sub' => $proposal_sub,
            'participation_step' => $current,
            'participation_next_step' => $next,
            'participation_can_go_next' => $can_go_next,
            'participation_step_number' => $step_number,
            'participation_stages_total' => $stages_total > 0 ? $stages_total : 1,
            'notice_url' => $notice_url,
            'participation_url' => $participation_url,
            'participation_stages' => $participation_stages,
            'participation_flow_json' => json_encode(array(
                'current_step' => $current,
                'next_required_step' => $next,
                'gates' => $gates,
                'stages' => $participation_stages,
                'approval_status' => (string) ($flow['approval_status'] ?? ''),
                'qualification_status' => (string) ($flow['qualification_status'] ?? ''),
                'proposal_sub' => $proposal_sub,
            ), JSON_UNESCAPED_UNICODE),
        ));
        $this->setThemeTemplate('html/cabinet/supplier/tender-participation.html');
    }
}
