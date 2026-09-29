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
        $app_counts = (new pb2bTenderCollection())->applicationCountsForTender((int) $tender->id);

        $items = $tender->getItemsForView();
        $items_max_total = $tender->getItemsMaxTotal();
        $invitations = (array) ($extra['invitations'] ?? []);
        $card = array_merge(
            pb2bTenderResource::make($tender)->resolve(),
            $app_counts,
            array('invited_count' => count($invitations))
        );

        $status_name = (string) ($card['status']['name'] ?? '');
        $status_tone = 'info';
        if (mb_strpos($status_name, 'Отмен') !== false || mb_strpos($status_name, 'Отозв') !== false) {
            $status_tone = 'error';
        } elseif (
            mb_strpos($status_name, 'Приём') !== false
            || mb_strpos($status_name, 'Сравнен') !== false
            || mb_strpos($status_name, 'Подан') !== false
        ) {
            $status_tone = 'success';
        } elseif (mb_strpos($status_name, 'Черновик') !== false || mb_strpos($status_name, 'Ожид') !== false) {
            $status_tone = 'warning';
        }

        $days_left_label = '';
        $end_at = trim((string) ($card['end_at'] ?? ''));
        $deadline_expired = false;
        if ($end_at !== '' && strpos($end_at, '0000-00-00') !== 0) {
            $end_ts = strtotime($end_at);
            if ($end_ts) {
                $today = strtotime('today');
                $end_day = strtotime(date('Y-m-d', $end_ts));
                $diff = (int) round(($end_day - $today) / 86400);
                if ($diff < 0) {
                    $days_left_label = 'Срок истёк';
                    $deadline_expired = true;
                } elseif ($diff === 0) {
                    $days_left_label = 'Сегодня';
                } elseif ($diff === 1) {
                    $days_left_label = 'Остался 1 день';
                } else {
                    $days_left_label = 'Осталось ' . $diff . ' дн.';
                }
            }
        }

        $display_budget = (float) ($card['budget'] ?? 0);
        if ($display_budget <= 0 && $items_max_total > 0) {
            $display_budget = $items_max_total;
        }

        $this->view->assign([
            'tender' => $tender,
            'card' => $card,
            'participants_count' => (int) ($app_counts['participants_count'] ?? 0),
            'proposals_count' => (int) ($app_counts['proposals_count'] ?? 0),
            'invited_count' => count($invitations),
            'criteria' => (array) ($extra['criteria'] ?? []),
            'invitations' => $invitations,
            'classifiers' => (array) ($extra['classifiers'] ?? []),
            'items' => $items,
            'items_max_total' => $items_max_total,
            'display_budget' => $display_budget,
            'organizer' => $company,
            'status_tone' => $status_tone,
            'days_left_label' => $days_left_label,
            'deadline_expired' => $deadline_expired,
            'ds_tabs' => array(
                array('id' => 'overview', 'label' => 'Обзор', 'active' => true),
                array('id' => 'questions', 'label' => 'Вопросы', 'disabled' => true),
                array(
                    'id' => 'participants',
                    'label' => 'Участники',
                    'count' => (int) ($app_counts['participants_count'] ?? 0),
                ),
                array('id' => 'evaluation', 'label' => 'Оценка', 'disabled' => true),
                array('id' => 'results', 'label' => 'Результаты', 'disabled' => true),
            ),
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
        $items = (array) ($notice['items'] ?? []);

        $organizer = null;
        $tender = new pb2bTender($resolved_id);
        $organizer_id = (int) ($tender->data['organizer_company_id'] ?? 0);
        if ($organizer_id > 0) {
            $organizer = new pb2bCompany($organizer_id);
            if (!(int) $organizer->id) {
                $organizer = null;
            }
        }

        $summary_city = '';
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $place = trim((string) ($item['delivery_place'] ?? ''));
            if ($place !== '') {
                $summary_city = $place;
                break;
            }
        }

        $summary_category = '';
        $classifier_names = array();
        foreach ((array) ($extra['classifiers'] ?? array()) as $classifier) {
            if (!is_array($classifier)) {
                continue;
            }
            $name = trim((string) ($classifier['classifier_name'] ?? $classifier['name'] ?? $classifier['title'] ?? ''));
            if ($name === '') {
                continue;
            }
            $classifier_names[] = $name;
            $code = (string) ($classifier['classifier_type_code'] ?? '');
            if ($code === 'category' && $summary_category === '') {
                $summary_category = $name;
            }
        }
        if ($summary_category === '' && $classifier_names) {
            $summary_category = implode(', ', $classifier_names);
        }

        $items_max_total = $tender->getItemsMaxTotal();

        $status_name = (string) ($card['status']['name'] ?? '');
        $status_tone = 'info';
        if (mb_strpos($status_name, 'Отмен') !== false || mb_strpos($status_name, 'Отозв') !== false) {
            $status_tone = 'error';
        } elseif (mb_strpos($status_name, 'Приём') !== false || mb_strpos($status_name, 'Сравнен') !== false || mb_strpos($status_name, 'Подан') !== false) {
            $status_tone = 'success';
        } elseif (mb_strpos($status_name, 'Черновик') !== false || mb_strpos($status_name, 'Ожид') !== false) {
            $status_tone = 'warning';
        }

        $days_left_label = '';
        $end_at = trim((string) ($card['end_at'] ?? ''));
        if ($end_at !== '' && strpos($end_at, '0000-00-00') !== 0) {
            $end_ts = strtotime($end_at);
            if ($end_ts) {
                $today = strtotime('today');
                $end_day = strtotime(date('Y-m-d', $end_ts));
                $diff = (int) round(($end_day - $today) / 86400);
                if ($diff === 0) {
                    $days_left_label = 'Сегодня';
                } elseif ($diff === 1) {
                    $days_left_label = 'Остался 1 день';
                } elseif ($diff > 1) {
                    $days_left_label = 'Осталось ' . $diff . ' дн.';
                }
            }
        }

        $application = $notice['application'] ?? null;
        $participation_url = '/cabinet/supplier/tender/' . $resolved_id . '/participation/';

        $this->view->assign([
            'tender_id' => $resolved_id,
            'card' => $card,
            'items' => $items,
            'documents' => (array) ($notice['documents'] ?? []),
            'criteria' => (array) ($notice['criteria'] ?? []),
            'application' => $application,
            'gates' => (array) ($notice['gates'] ?? []),
            'participation_url' => $participation_url,
            'classifiers' => (array) ($extra['classifiers'] ?? []),
            'organizer' => $organizer,
            'supplier' => $company,
            'summary_city' => $summary_city,
            'summary_category' => $summary_category,
            'requires_prequalification' => (int) ($tender->data['past_prequal_tender_id'] ?? 0) > 0,
            'items_max_total' => $items_max_total,
            'status_tone' => $status_tone,
            'days_left_label' => $days_left_label,
            'ds_tabs' => array(
                array('id' => 'notice', 'label' => 'Извещение', 'active' => true),
                array('id' => 'procurementDocs', 'label' => 'Закупочная документация'),
                array('id' => 'positions', 'label' => 'Позиции', 'count' => count($items)),
                array('id' => 'documents', 'label' => 'Документы', 'count' => count((array) ($notice['documents'] ?? array()))),
                array('id' => 'questions', 'label' => 'Вопросы'),
                array('id' => 'nonPrice', 'label' => 'Неценовые критерии'),
            ),
        ]);
        $this->setThemeTemplate('html/cabinet/supplier/tender.html');
    }
}
