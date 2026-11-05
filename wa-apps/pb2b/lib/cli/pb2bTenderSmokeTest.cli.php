<?php

/**
 * Smoke для тендеров ЗЦ: таблицы, фасады, create→items→publish → accepting_applications,
 * persist черновика заявки и строки цены.
 */
class pb2bTenderSmokeTestCli extends waCliController
{
    private int $passed = 0;
    private int $failed = 0;
    private ?pb2bCompany $winnerApiCompany = null;
    private ?waContact $winnerApiActor = null;

    public function execute()
    {
        $this->out('=== Tender smoke test (CLI) ===');

        $this->testDbTables();
        $this->testNoProcedureLegacy();
        $this->testCompanyFacadeRemoved();
        $this->testObjectPersistHelpers();
        $this->testCreateItemsPublishAndApplicationPersist();
        $this->testStartReviewAndRanking();
        $this->testWinnerSelection();
        $this->testWinnerApiControllers();

        $this->out('');
        $this->out("PASSED: {$this->passed}, FAILED: {$this->failed}");
        exit($this->failed > 0 ? 1 : 0);
    }

    private function testDbTables(): void
    {
        $model = new waModel();
        foreach (array(
            'pb2b_tender',
            'pb2b_tender_classifier',
            'pb2b_tender_state_log',
            'pb2b_tender_invitation',
            'pb2b_tender_criterion',
            'pb2b_tender_item',
            'pb2b_tender_document',
            'pb2b_tender_application',
            'pb2b_tender_application_item',
            'pb2b_tender_application_document',
            'pb2b_tender_application_criterion',
        ) as $table) {
            $exists = $model->query('SHOW TABLES LIKE ?', $table)->fetch();
            $this->assert("Table {$table}", !empty($exists));
        }
    }

    private function testNoProcedureLegacy(): void
    {
        $dir = wa()->getAppPath('lib', 'pb2b');
        $grep = shell_exec('grep -r --exclude="*SmokeTest*" pb2bProcedure ' . escapeshellarg($dir) . ' 2>/dev/null') ?: '';
        $this->assert('No pb2bProcedure in pb2b', trim($grep) === '');
    }

    private function testCompanyFacadeRemoved(): void
    {
        $this->assert(
            'Company has no tenderSaveWizardFromBuyer',
            !method_exists('pb2bCompany', 'tenderSaveWizardFromBuyer')
        );
        $this->assert(
            'Company has no tenderPublishFromBuyer',
            !method_exists('pb2bCompany', 'tenderPublishFromBuyer')
        );
        $this->assert(
            'Tender has no saveWizardStep',
            !method_exists('pb2bTender', 'saveWizardStep')
        );
        $this->assert(
            'Tender has no validateStep',
            !method_exists('pb2bTender', 'validateStep')
        );
    }

    private function testObjectPersistHelpers(): void
    {
        $methods = pb2bTender::getCreateModalMethods();
        $this->assert('Create modal methods is array', is_array($methods) && count($methods) > 0);

        $codes = array();
        foreach ($methods as $row) {
            if (!empty($row['code'])) {
                $codes[] = (string) $row['code'];
            }
        }
        $this->assert(
            'Modal lists price_request',
            in_array(pb2bTenderType::PRICE_REQUEST->value, $codes, true)
        );
        $this->assert('TenderApplication draft is allowed', pb2bTenderApplication::isAllowedStatus('draft'));
        $this->assert('TenderApplication bogus status rejected', !pb2bTenderApplication::isAllowedStatus('published'));
        $this->assert(
            'TenderApplication draft→submitted allowed',
            pb2bTenderApplication::isAllowedTransition('draft', 'submitted')
        );
        $this->assert(
            'TenderApplication withdrawn is frozen',
            !pb2bTenderApplication::isAllowedTransition('withdrawn', 'draft')
        );
        $empty = new pb2bTenderApplication();
        $this->assert('New TenderApplication cannot submit', !$empty->canSubmit());
        $this->assert(
            'TenderPolicy has viewAsSupplier',
            method_exists('pb2bTenderPolicy', 'viewAsSupplier')
        );
        $empty_tender = new pb2bTender();
        $empty_company = new pb2bCompany();
        $this->assert(
            'Empty supplier cannot view tender',
            !pb2bTenderPolicy::viewAsSupplier($empty_tender, $empty_company, false)
        );
        $this->assert(
            'Empty application not viewable',
            !pb2bTenderApplicationPolicy::view($empty, $empty_company)
        );
        $this->assert(
            'Empty application not viewable by buyer',
            !pb2bTenderApplicationPolicy::viewAsBuyer($empty, $empty_company)
        );

        $masked = pb2bTenderApplicationResource::make(array(
            'id' => 1,
            'tender_id' => 2,
            'supplier_company_id' => 3,
            'status' => 'submitted',
            'approval_status' => 'pending',
            'qualification_status' => 'pending',
            'admission_status' => 'pending',
        ))->withPrices(false)->withItems(array(
            array(
                'id' => 10,
                'application_id' => 1,
                'tender_item_id' => 20,
                'qty' => 2,
                'unit' => 'шт.',
                'price_per_unit' => 55.5,
            ),
        ))->resolve();
        $this->assert(
            'Masked item has no price_per_unit',
            !array_key_exists('price_per_unit', $masked['items'][0] ?? array())
        );
        $this->assert(
            'Masked item has no amount',
            !array_key_exists('amount', $masked['items'][0] ?? array())
        );
        $this->assert('prices_visible is off', empty($masked['prices_visible']));

        $open = pb2bTenderApplicationResource::make(array(
            'id' => 1,
            'tender_id' => 2,
            'supplier_company_id' => 3,
            'status' => 'submitted',
            'approval_status' => 'pending',
            'qualification_status' => 'pending',
            'admission_status' => 'pending',
        ))->withPrices(true)->withItems(array(
            array(
                'id' => 10,
                'application_id' => 1,
                'tender_item_id' => 20,
                'qty' => 2,
                'price_per_unit' => 55.5,
            ),
        ))->resolve();
        $this->assert(
            'Owner sees price_per_unit',
            array_key_exists('price_per_unit', $open['items'][0] ?? array())
            && (float) ($open['items'][0]['price_per_unit'] ?? 0) === 55.5
        );
        $this->assert(
            'Buyer prices hidden in accepting_applications',
            !pb2bTenderApplicationResource::isBuyerPriceVisible(pb2bTenderStatus::ACCEPTING_APPLICATIONS->value)
        );
        $this->assert(
            'Buyer prices visible after opening applications',
            pb2bTenderApplicationResource::isBuyerPriceVisible(pb2bTenderStatus::OPENING_APPLICATIONS->value)
        );
        $this->assert(
            'TenderApplicationService has submit',
            method_exists('pb2bTenderApplicationService', 'submit')
        );
        $this->assert(
            'TenderApplicationService has getForBuyer',
            method_exists('pb2bTenderApplicationService', 'getForBuyer')
        );
        $this->assert(
            'TenderService has startReviewForBuyer',
            method_exists('pb2bTenderService', 'startReviewForBuyer')
        );
        $this->assert(
            'TenderApplicationService has rankingForBuyer',
            method_exists('pb2bTenderApplicationService', 'rankingForBuyer')
        );
        $this->assert(
            'TenderApplicationService has selectWinnerAll',
            method_exists('pb2bTenderApplicationService', 'selectWinnerAll')
        );
        $this->assert(
            'TenderService has completeWinnerSelectionForBuyer',
            method_exists('pb2bTenderService', 'completeWinnerSelectionForBuyer')
        );
        $this->assert(
            'TenderService has finishWithoutWinnerForBuyer',
            method_exists('pb2bTenderService', 'finishWithoutWinnerForBuyer')
        );
        $this->assert(
            'TenderPolicy has selectWinner',
            method_exists('pb2bTenderPolicy', 'selectWinner')
        );
        $this->assert(
            'TenderPolicy has startReview',
            method_exists('pb2bTenderPolicy', 'startReview')
        );
    }

    private function testCreateItemsPublishAndApplicationPersist(): void
    {
        $company_row = (new pb2bCompanyModel())->query(
            'SELECT id, contact_id FROM pb2b_company WHERE buyer = 1 AND IFNULL(deleted, 0) = 0 ORDER BY id ASC LIMIT 1'
        )->fetchAssoc();
        if (empty($company_row['id'])) {
            $this->assert('Buyer company for create→publish', false, 'нет компании-покупателя');
            return;
        }

        $company = new pb2bCompany((int) $company_row['id']);
        $actor = $company->getContact();
        if (!$actor) {
            $contact_id = (int) ($company_row['contact_id'] ?? 0);
            $actor = $contact_id > 0 ? new waContact($contact_id) : null;
        }
        if (!$actor || !$actor->getId()) {
            $this->assert('Buyer company has contact', false);
            return;
        }

        $type_id = pb2bTenderType::PRICE_REQUEST->id();
        $title = 'Smoke ЗЦ ' . date('H:i:s');

        $service = new pb2bTenderService();
        $tender = null;
        $application_id = 0;
        try {
            $tender = $service->createFromBuyer(
                (int) $company->id,
                new pb2bTenderDto(array(
                    'type' => $type_id,
                    'title' => $title,
                )),
                $actor
            );
            $this->assert('Create price_request draft', (int) $tender->id > 0);

            $empty_flow = (new pb2bTenderApplicationService())->resolveParticipationFlow(null, $tender);
            $empty_stage_ids = array_column((array) ($empty_flow['stages'] ?? array()), 'id');
            $this->assert(
                'No nonprice stage when tender has no criteria',
                !in_array(pb2bTenderParticipationStep::NON_PRICE->value, $empty_stage_ids, true)
            );

            $service->updateFromBuyer(
                (int) $tender->id,
                (int) $company->id,
                new pb2bTenderDto(array(
                    'title' => $title,
                    'end_at' => date('Y-m-d H:i:s', time() + 7 * 86400),
                    'approval_required' => 1,
                    'criteria' => array(
                        array(
                            'name' => 'Smoke допуск',
                            'type' => 'non_price',
                            'is_mandatory' => 1,
                            'description' => 'CLI',
                        ),
                    ),
                    'items' => array(
                        array(
                            'name' => 'Smoke позиция',
                            'qty' => 2,
                            'unit' => 'шт.',
                            'max_price_no_vat' => 100,
                            'vat_rate' => '20%',
                            'delivery_place' => 'Москва',
                            'sort' => 0,
                        ),
                    ),
                )),
                $actor
            );

            $detail = $service->getDetailFromBuyer((int) $tender->id, $company);
            $this->assert(
                'GET returns saved item',
                !empty($detail['items'][0]['name']) && $detail['items'][0]['name'] === 'Smoke позиция'
            );
            $criteria_flow = (new pb2bTenderApplicationService())->resolveParticipationFlow(null, $tender);
            $criteria_stage_ids = array_column((array) ($criteria_flow['stages'] ?? array()), 'id');
            $this->assert(
                'Nonprice stage shown when tender has criteria',
                in_array(pb2bTenderParticipationStep::NON_PRICE->value, $criteria_stage_ids, true)
            );

            $published = $service->publishFromBuyer((int) $tender->id, (int) $company->id, 'smoke', $actor);
            $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
            $status_code = (string) ($statuses[(int) ($published->data['status'] ?? 0)]['code'] ?? '');
            $this->assert(
                'Publish status is accepting_applications',
                $status_code === pb2bTenderStatus::ACCEPTING_APPLICATIONS->value,
                $status_code
            );

            $item_row = (new waModel())->query(
                'SELECT COUNT(*) AS cnt FROM pb2b_tender_item WHERE tender_id = ?',
                (int) $tender->id
            )->fetchAssoc();
            $item_cnt = (int) ($item_row['cnt'] ?? 0);
            $this->assert('pb2b_tender_item has rows after publish', $item_cnt >= 1, (string) $item_cnt);

            $log_row = (new waModel())->query(
                'SELECT COUNT(*) AS cnt FROM pb2b_tender_state_log WHERE tender_id = ?',
                (int) $tender->id
            )->fetchAssoc();
            $log_cnt = (int) ($log_row['cnt'] ?? 0);
            $this->assert('state_log has publish transitions', $log_cnt >= 2, (string) $log_cnt);

            $application_id = $this->persistSmokeApplication($tender, $detail, (int) $company->id);
        } catch (Exception $e) {
            $this->assert('create→items→publish→application', false, $e->getMessage());
        }

        $this->deleteSmokeApplication($application_id);
        if ($tender && (int) $tender->id > 0) {
            try {
                (new pb2bTenderModel())->updateById((int) $tender->id, array('is_deleted' => 1));
            } catch (Exception $e) {
                $this->out('NOTE: не удалось пометить smoke-тендер удалённым: ' . $e->getMessage());
            }
        }
    }

    /**
     * 7.4: start-review + ranking (цены, ранг, rejected вне таблицы, идемпотентность).
     */
    private function testStartReviewAndRanking(): void
    {
        $this->out('--- 7.4 start-review + ranking ---');

        $buyer_row = (new pb2bCompanyModel())->query(
            'SELECT id, contact_id FROM pb2b_company WHERE buyer = 1 AND IFNULL(deleted, 0) = 0 ORDER BY id ASC LIMIT 1'
        )->fetchAssoc();
        if (empty($buyer_row['id'])) {
            $this->assert('Buyer for ranking smoke', false, 'нет buyer');
            return;
        }
        $buyer = new pb2bCompany((int) $buyer_row['id']);
        $actor = $buyer->getContact();
        if (!$actor) {
            $cid = (int) ($buyer_row['contact_id'] ?? 0);
            $actor = $cid > 0 ? new waContact($cid) : null;
        }
        if (!$actor || !$actor->getId()) {
            $this->assert('Buyer contact for ranking smoke', false);
            return;
        }

        $suppliers = (new pb2bCompanyModel())->query(
            'SELECT id FROM pb2b_company
             WHERE supplier = 1 AND IFNULL(deleted, 0) = 0 AND id <> ?
             ORDER BY id ASC LIMIT 3',
            (int) $buyer->id
        )->fetchAll();
        if (!is_array($suppliers) || count($suppliers) < 2) {
            $this->assert('Need ≥2 suppliers for ranking smoke', false, 'поставщиков мало');
            return;
        }

        $type_id = pb2bTenderType::PRICE_REQUEST->id();
        $service = new pb2bTenderService();
        $app_service = new pb2bTenderApplicationService();
        $tender = null;
        $app_ids = array();

        try {
            $tender = $service->createFromBuyer(
                (int) $buyer->id,
                new pb2bTenderDto(array(
                    'type' => $type_id,
                    'title' => 'Smoke ranking ' . date('H:i:s'),
                )),
                $actor
            );
            $service->updateFromBuyer(
                (int) $tender->id,
                (int) $buyer->id,
                new pb2bTenderDto(array(
                    'title' => 'Smoke ranking ' . date('H:i:s'),
                    'end_at' => date('Y-m-d H:i:s', time() + 3 * 86400),
                    'itemized_enabled' => 0,
                    'approval_required' => 0,
                    'criteria' => array(
                        array(
                            'name' => 'Smoke optional',
                            'type' => 'non_price',
                            'is_mandatory' => 0,
                            'description' => 'CLI',
                        ),
                    ),
                    'items' => array(
                        array(
                            'name' => 'Smoke lot',
                            'qty' => 10,
                            'unit' => 'шт.',
                            'max_price_no_vat' => 1000,
                            'vat_rate' => '20%',
                            'delivery_place' => 'Москва',
                            'sort' => 0,
                        ),
                    ),
                )),
                $actor
            );
            $service->publishFromBuyer((int) $tender->id, (int) $buyer->id, 'smoke ranking', $actor);
            $tender = new pb2bTender((int) $tender->id);
            $detail = $service->getDetailFromBuyer((int) $tender->id, $buyer);
            $tender_item_id = (int) ($detail['items'][0]['id'] ?? 0);
            $this->assert('Ranking smoke has tender item', $tender_item_id > 0);

            $review = pb2bTenderResource::make($tender)->resolve()['review'];
            $this->assert(
                'Before end_at review.can_start=false',
                (int) ($review['can_start'] ?? 1) === 0,
                (string) ($review['can_start'] ?? '')
            );

            $blocked_review = false;
            try {
                $service->startReviewForBuyer((int) $tender->id, (int) $buyer->id, 'early', $actor);
            } catch (waException $e) {
                $blocked_review = ((int) $e->getCode() === pb2bHttpStatus::CONFLICT);
                $this->assert(
                    'start-review before end_at → 409',
                    $blocked_review,
                    $e->getMessage()
                );
            }
            if (!$blocked_review) {
                $this->assert('start-review before end_at → 409', false, 'исключение не выброшено');
            }

            $blocked_rank = false;
            try {
                $app_service->rankingForBuyer((int) $tender->id, (int) $buyer->id);
            } catch (waException $e) {
                $blocked_rank = ((int) $e->getCode() === pb2bHttpStatus::CONFLICT);
                $this->assert(
                    'ranking before review → 409',
                    $blocked_rank,
                    $e->getMessage()
                );
            }
            if (!$blocked_rank) {
                $this->assert('ranking before review → 409', false, 'исключение не выброшено');
            }

            // cheap / expensive / rejected
            $prices = array(40.0, 90.0, 50.0);
            $admissions = array('pending', 'pending', 'rejected');
            $count = min(3, count($suppliers));
            for ($i = 0; $i < $count; $i++) {
                $app_ids[] = $this->createSubmittedSmokeApplication(
                    (int) $tender->id,
                    (int) $suppliers[$i]['id'],
                    $tender_item_id,
                    10,
                    $prices[$i],
                    $admissions[$i]
                );
            }
            $this->assert('Created ≥2 submitted apps', count(array_filter($app_ids)) >= 2);

            $buyer_list = $app_service->getForBuyer((int) $tender->id, (int) $buyer->id);
            $this->assert(
                'prices_visible=0 while priem',
                (int) ($buyer_list['prices_visible'] ?? 1) === 0
            );
            $first_detail = null;
            foreach ((array) ($buyer_list['applications'] ?? array()) as $row) {
                if (($row['status']['code'] ?? '') === 'submitted') {
                    $first_detail = $app_service->getForBuyer(
                        (int) $tender->id,
                        (int) $buyer->id,
                        (int) $row['id']
                    );
                    break;
                }
            }
            if ($first_detail) {
                $items = (array) (($first_detail['application']['items'] ?? array()));
                $has_price = false;
                foreach ($items as $item) {
                    if (is_array($item) && array_key_exists('price_per_unit', $item)) {
                        $has_price = true;
                    }
                }
                $this->assert('GET detail hides price_per_unit in priem', !$has_price);
            } else {
                $this->assert('GET detail hides price_per_unit in priem', false, 'нет submitted');
            }

            (new pb2bTenderModel())->updateById((int) $tender->id, array(
                'end_at' => date('Y-m-d H:i:s', time() - 60),
            ));
            $tender = new pb2bTender((int) $tender->id);
            $review = pb2bTenderResource::make($tender)->resolve()['review'];
            $this->assert(
                'After end_at review.can_start=true',
                (int) ($review['can_start'] ?? 0) === 1,
                json_encode($review)
            );

            $started = $service->startReviewForBuyer(
                (int) $tender->id,
                (int) $buyer->id,
                'smoke start-review',
                $actor
            );
            $this->assert(
                'start-review → review_and_admission',
                (string) ($started['tender']['status']['code'] ?? '') === pb2bTenderStatus::REVIEW_AND_ADMISSION->value,
                (string) ($started['tender']['status']['code'] ?? '')
            );
            $this->assert(
                'start-review sets review.is_started',
                (int) ($started['tender']['review']['is_started'] ?? 0) === 1
            );
            $this->assert(
                'start-review returns ranking payload',
                is_array($started['ranking'] ?? null)
            );

            $again = $service->startReviewForBuyer(
                (int) $tender->id,
                (int) $buyer->id,
                'smoke idempotent',
                $actor
            );
            $this->assert(
                'start-review idempotent status',
                (string) ($again['tender']['status']['code'] ?? '') === pb2bTenderStatus::REVIEW_AND_ADMISSION->value,
                (string) ($again['tender']['status']['code'] ?? '')
            );

            $tender = new pb2bTender((int) $tender->id);
            $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
            $final_code = (string) ($statuses[(int) ($tender->data['status'] ?? 0)]['code'] ?? '');
            $this->assert(
                'Status is not evaluation',
                $final_code !== pb2bTenderStatus::EVALUATION->value,
                $final_code
            );

            $ranking = $app_service->rankingForBuyer((int) $tender->id, (int) $buyer->id);
            $apps = (array) ($ranking['applications'] ?? array());
            $this->assert(
                'Ranking excludes rejected (2 apps)',
                count($apps) === 2,
                'count=' . count($apps)
            );

            $by_price = array();
            foreach ($apps as $row) {
                $by_price[(string) ($row['total'] ?? '')] = (int) ($row['rank'] ?? 0);
            }
            // qty 10 * 40 = 400 → rank 1; 10 * 90 = 900 → rank 2
            $this->assert(
                'Cheaper total has rank 1',
                (int) ($by_price['400'] ?? $by_price['400.00'] ?? 0) === 1
                || $this->rankingHasRankForTotal($apps, 400.0, 1),
                json_encode($apps, JSON_UNESCAPED_UNICODE)
            );
            $this->assert(
                'Expensive total has rank 2',
                $this->rankingHasRankForTotal($apps, 900.0, 2),
                json_encode($apps, JSON_UNESCAPED_UNICODE)
            );

            $other_buyer = (new pb2bCompanyModel())->query(
                'SELECT id FROM pb2b_company
                 WHERE buyer = 1 AND IFNULL(deleted, 0) = 0 AND id <> ?
                 ORDER BY id ASC LIMIT 1',
                (int) $buyer->id
            )->fetchAssoc();
            if (!empty($other_buyer['id'])) {
                $foreign_blocked = false;
                try {
                    $app_service->rankingForBuyer((int) $tender->id, (int) $other_buyer['id']);
                } catch (waException $e) {
                    $code = (int) $e->getCode();
                    $foreign_blocked = in_array($code, array(
                        pb2bHttpStatus::FORBIDDEN,
                        pb2bHttpStatus::NOT_FOUND,
                    ), true);
                    $this->assert(
                        'Foreign buyer ranking → 403/404',
                        $foreign_blocked,
                        $e->getMessage() . ' code=' . $code
                    );
                }
                if (!$foreign_blocked) {
                    $this->assert('Foreign buyer ranking → 403/404', false, 'доступ дан чужому');
                }
            } else {
                $this->out('NOTE: нет второго buyer — skip foreign ranking check');
            }
        } catch (Exception $e) {
            $this->assert('start-review+ranking smoke', false, $e->getMessage());
        }

        foreach ($app_ids as $aid) {
            $this->deleteSmokeApplication((int) $aid);
        }
        if ($tender && (int) $tender->id > 0) {
            try {
                (new pb2bTenderModel())->updateById((int) $tender->id, array('is_deleted' => 1));
            } catch (Exception $e) {
                $this->out('NOTE: ranking smoke tender cleanup: ' . $e->getMessage());
            }
        }
    }

    /**
     * Wave 3 / 2.9: select / clear / complete / finish-without (после start-review).
     */
    private function testWinnerSelection(): void
    {
        $this->out('--- 2.9 winner selection ---');

        $buyer_row = (new pb2bCompanyModel())->query(
            'SELECT id, contact_id FROM pb2b_company WHERE buyer = 1 AND IFNULL(deleted, 0) = 0 ORDER BY id ASC LIMIT 1'
        )->fetchAssoc();
        if (empty($buyer_row['id'])) {
            $this->assert('Buyer for winner smoke', false, 'нет buyer');
            return;
        }
        $buyer = new pb2bCompany((int) $buyer_row['id']);
        $actor = $buyer->getContact();
        if (!$actor) {
            $cid = (int) ($buyer_row['contact_id'] ?? 0);
            $actor = $cid > 0 ? new waContact($cid) : null;
        }
        if (!$actor || !$actor->getId()) {
            $this->assert('Buyer contact for winner smoke', false);
            return;
        }

        $suppliers = (new pb2bCompanyModel())->query(
            'SELECT id FROM pb2b_company
             WHERE supplier = 1 AND IFNULL(deleted, 0) = 0 AND id <> ?
             ORDER BY id ASC LIMIT 2',
            (int) $buyer->id
        )->fetchAll();
        if (!is_array($suppliers) || count($suppliers) < 2) {
            $this->assert('Need ≥2 suppliers for winner smoke', false, 'поставщиков мало');
            return;
        }

        $type_id = pb2bTenderType::PRICE_REQUEST->id();
        $service = new pb2bTenderService();
        $app_service = new pb2bTenderApplicationService();
        $mode_all = pb2bTender::winnerSelectionModeCodeToId('all');
        $mode_none = pb2bTender::winnerSelectionModeCodeToId('none');
        $this->assert('winner mode all id > 0', $mode_all > 0, (string) $mode_all);
        $this->assert('winner mode none id > 0', $mode_none > 0, (string) $mode_none);

        $tender = null;
        $tender_finish = null;
        $app_ids = array();
        $finish_app_ids = array();

        try {
            $prepared = $this->prepareReviewedWinnerSmokeTender(
                $service,
                $buyer,
                $actor,
                $type_id,
                $suppliers,
                'Smoke winner'
            );
            $tender = $prepared['tender'];
            $app_ids = $prepared['app_ids'];
            $winner_app_id = (int) ($prepared['cheap_app_id'] ?? 0);
            $this->assert('Winner smoke cheap app id', $winner_app_id > 0);

            $selected = $app_service->selectWinnerAll(
                (int) $tender->id,
                (int) $buyer->id,
                $winner_app_id
            );
            $this->assert(
                'selectWinnerAll mode=all',
                (string) ($selected['winner_selection_mode'] ?? '') === 'all',
                (string) ($selected['winner_selection_mode'] ?? '')
            );
            $this->assert(
                'selectWinnerAll awards.application_id',
                (int) ($selected['awards']['application_id'] ?? 0) === $winner_app_id,
                json_encode($selected['awards'] ?? array())
            );
            $this->assert(
                'selectWinnerAll can_complete=1',
                (int) ($selected['can_complete'] ?? 0) === 1
            );

            $tender = new pb2bTender((int) $tender->id);
            $this->assert(
                'DB winner_selection_mode=all id',
                (int) ($tender->data['winner_selection_mode'] ?? 0) === $mode_all,
                (string) ($tender->data['winner_selection_mode'] ?? '')
            );
            $win_app = new pb2bTenderApplication($winner_app_id);
            $this->assert(
                'Winner application is_winner=1',
                !empty($win_app->data['is_winner'])
            );
            $item_rows = (new pb2bTenderApplicationItemModel())->getByField(
                'application_id',
                $winner_app_id,
                true
            );
            $item_winner = 0;
            foreach (is_array($item_rows) ? $item_rows : array() as $ir) {
                if (!empty($ir['is_winner'])) {
                    $item_winner++;
                }
            }
            $this->assert('selectWinnerAll keeps item is_winner=0', $item_winner === 0);

            $cleared = $app_service->clearWinnerDraft((int) $tender->id, (int) $buyer->id);
            $this->assert(
                'clearWinnerDraft mode=none',
                (string) ($cleared['winner_selection_mode'] ?? '') === 'none',
                (string) ($cleared['winner_selection_mode'] ?? '')
            );
            $this->assert(
                'clearWinnerDraft can_complete=0',
                (int) ($cleared['can_complete'] ?? 1) === 0
            );
            $win_app = new pb2bTenderApplication($winner_app_id);
            $this->assert(
                'After clear application is_winner=0',
                empty($win_app->data['is_winner'])
            );

            $positional_blocked = false;
            try {
                $app_service->selectWinnerPositional(
                    (int) $tender->id,
                    (int) $buyer->id,
                    array(array(
                        'tender_item_id' => (int) $prepared['tender_item_id'],
                        'application_id' => $winner_app_id,
                    ))
                );
            } catch (waException $e) {
                $positional_blocked = ((int) $e->getCode() === pb2bHttpStatus::CONFLICT);
                $this->assert(
                    'positional without itemized → 409',
                    $positional_blocked,
                    $e->getMessage()
                );
            }
            if (!$positional_blocked) {
                $this->assert('positional without itemized → 409', false, 'исключение не выброшено');
            }

            $complete_blocked = false;
            try {
                $service->completeWinnerSelectionForBuyer(
                    (int) $tender->id,
                    (int) $buyer->id,
                    'smoke empty complete',
                    $actor
                );
            } catch (waException $e) {
                $complete_blocked = ((int) $e->getCode() === pb2bHttpStatus::CONFLICT);
                $this->assert(
                    'complete without winner → 409',
                    $complete_blocked,
                    $e->getMessage()
                );
            }
            if (!$complete_blocked) {
                $this->assert('complete without winner → 409', false, 'исключение не выброшено');
            }

            $app_service->selectWinnerAll((int) $tender->id, (int) $buyer->id, $winner_app_id);
            $done = $service->completeWinnerSelectionForBuyer(
                (int) $tender->id,
                (int) $buyer->id,
                'smoke complete winner',
                $actor
            );
            $this->assert(
                'complete → summarizing_results',
                (string) ($done['tender']['status']['code'] ?? '') === pb2bTenderStatus::SUMMARIZING_RESULTS->value,
                (string) ($done['tender']['status']['code'] ?? '')
            );
            $this->assert(
                'complete sets winner_selection_completed',
                (int) (($done['winner']['winner_selection_completed'] ?? 0)) === 1
            );
            $this->assert(
                'complete not finished_without_winner',
                (int) (($done['winner']['finished_without_winner'] ?? 1)) === 0
            );

            $again = $service->completeWinnerSelectionForBuyer(
                (int) $tender->id,
                (int) $buyer->id,
                'smoke complete idempotent',
                $actor
            );
            $this->assert(
                'complete idempotent podvedenie',
                (string) ($again['tender']['status']['code'] ?? '') === pb2bTenderStatus::SUMMARIZING_RESULTS->value
            );

            $select_blocked = false;
            try {
                $app_service->selectWinnerAll((int) $tender->id, (int) $buyer->id, $winner_app_id);
            } catch (waException $e) {
                $select_blocked = ((int) $e->getCode() === pb2bHttpStatus::CONFLICT);
                $this->assert(
                    'select after complete → 409',
                    $select_blocked,
                    $e->getMessage()
                );
            }
            if (!$select_blocked) {
                $this->assert('select after complete → 409', false, 'исключение не выброшено');
            }

            // Отдельный тендер: finish without winner
            $prepared2 = $this->prepareReviewedWinnerSmokeTender(
                $service,
                $buyer,
                $actor,
                $type_id,
                $suppliers,
                'Smoke finish-wo'
            );
            $tender_finish = $prepared2['tender'];
            $finish_app_ids = $prepared2['app_ids'];

            $empty_reason_blocked = false;
            try {
                $service->finishWithoutWinnerForBuyer(
                    (int) $tender_finish->id,
                    (int) $buyer->id,
                    '   ',
                    $actor
                );
            } catch (waException $e) {
                $empty_reason_blocked = ((int) $e->getCode() === pb2bHttpStatus::BAD_REQUEST);
                $this->assert(
                    'finish-without empty reason → 400',
                    $empty_reason_blocked,
                    $e->getMessage()
                );
            }
            if (!$empty_reason_blocked) {
                $this->assert('finish-without empty reason → 400', false, 'исключение не выброшено');
            }

            $finished = $service->finishWithoutWinnerForBuyer(
                (int) $tender_finish->id,
                (int) $buyer->id,
                'smoke no winner',
                $actor
            );
            $this->assert(
                'finish-without → summarizing_results',
                (string) ($finished['tender']['status']['code'] ?? '') === pb2bTenderStatus::SUMMARIZING_RESULTS->value,
                (string) ($finished['tender']['status']['code'] ?? '')
            );
            $this->assert(
                'finish-without completed=1',
                (int) (($finished['winner']['winner_selection_completed'] ?? 0)) === 1
            );
            $this->assert(
                'finish-without finished_without_winner=1',
                (int) (($finished['winner']['finished_without_winner'] ?? 0)) === 1
            );
            $this->assert(
                'finish-without awards empty',
                empty($finished['winner']['awards']['application_id'])
                && empty($finished['winner']['awards']['items']),
                json_encode($finished['winner']['awards'] ?? array())
            );
            $this->assert(
                'finish-without reason stored',
                (string) ($finished['winner']['finished_without_winner_reason'] ?? '') === 'smoke no winner'
            );
        } catch (Exception $e) {
            $this->assert('winner selection smoke', false, $e->getMessage());
        }

        foreach (array_merge($app_ids, $finish_app_ids) as $aid) {
            $this->deleteSmokeApplication((int) $aid);
        }
        foreach (array($tender, $tender_finish) as $t) {
            if ($t && (int) $t->id > 0) {
                try {
                    (new pb2bTenderModel())->updateById((int) $t->id, array('is_deleted' => 1));
                } catch (Exception $e) {
                    $this->out('NOTE: winner smoke tender cleanup: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Wave 3 / 3.5: wiring контроллеров winner/* (class + routing + executeBuyer).
     */
    private function testWinnerApiControllers(): void
    {
        $this->out('--- 3.5 winner API controllers ---');

        $classes = array(
            'pb2bFrontendApiBuyerTenderWinnerGetController',
            'pb2bFrontendApiBuyerTenderWinnerSelectController',
            'pb2bFrontendApiBuyerTenderWinnerClearController',
            'pb2bFrontendApiBuyerTenderWinnerCompleteController',
            'pb2bFrontendApiBuyerTenderWinnerFinishWithoutController',
        );
        foreach ($classes as $class) {
            $this->assert('class_exists ' . $class, class_exists($class));
        }

        $routing_path = wa()->getAppPath('lib/config/routing.php', 'pb2b');
        $routing = is_string($routing_path) && is_readable($routing_path)
            ? (string) file_get_contents($routing_path)
            : '';
        foreach (array(
            'winner/select/',
            'winner/clear/',
            'winner/complete/',
            'winner/finish-without/',
            'tender/<id>/winner/',
        ) as $needle) {
            $this->assert(
                'routing has ' . $needle,
                $routing !== '' && strpos($routing, $needle) !== false
            );
        }

        $buyer_row = (new pb2bCompanyModel())->query(
            'SELECT id, contact_id FROM pb2b_company WHERE buyer = 1 AND IFNULL(deleted, 0) = 0 ORDER BY id ASC LIMIT 1'
        )->fetchAssoc();
        if (empty($buyer_row['id']) || (int) ($buyer_row['contact_id'] ?? 0) <= 0) {
            $this->assert('Buyer+contact for winner API smoke', false, 'нет buyer.contact_id');
            return;
        }
        $buyer = new pb2bCompany((int) $buyer_row['id']);
        $actor = new waContact((int) $buyer_row['contact_id']);
        if (!$actor->getId()) {
            $this->assert('Buyer contact loads', false);
            return;
        }

        $suppliers = (new pb2bCompanyModel())->query(
            'SELECT id FROM pb2b_company
             WHERE supplier = 1 AND IFNULL(deleted, 0) = 0 AND id <> ?
             ORDER BY id ASC LIMIT 2',
            (int) $buyer->id
        )->fetchAll();
        if (!is_array($suppliers) || count($suppliers) < 2) {
            $this->assert('Need ≥2 suppliers for winner API smoke', false);
            return;
        }

        $type_id = pb2bTenderType::PRICE_REQUEST->id();
        $service = new pb2bTenderService();
        $tender = null;
        $tender_finish = null;
        $app_ids = array();
        $finish_app_ids = array();

        try {
            $this->winnerApiCompany = $buyer;
            $this->winnerApiActor = $actor;

            $prepared = $this->prepareReviewedWinnerSmokeTender(
                $service,
                $buyer,
                $actor,
                $type_id,
                $suppliers,
                'Smoke API winner'
            );
            $tender = $prepared['tender'];
            $app_ids = $prepared['app_ids'];
            $winner_app_id = (int) ($prepared['cheap_app_id'] ?? 0);
            $tender_id = (int) $tender->id;

            $get = $this->runWinnerBuyerController(
                'pb2bFrontendApiBuyerTenderWinnerGetController',
                $tender_id,
                array()
            );
            $this->assert(
                'GET winner error=false',
                isset($get['error']) && $get['error'] === false,
                json_encode($get)
            );
            $this->assert(
                'GET winner mode=none',
                (string) ($get['winner_selection_mode'] ?? '') === 'none',
                (string) ($get['winner_selection_mode'] ?? '')
            );
            $this->assert(
                'GET winner can_edit=1',
                (int) ($get['can_edit'] ?? 0) === 1
            );

            $selected = $this->runWinnerBuyerController(
                'pb2bFrontendApiBuyerTenderWinnerSelectController',
                $tender_id,
                array(
                    'mode' => 'all',
                    'application_id' => $winner_app_id,
                )
            );
            $this->assert(
                'POST select all mode=all',
                (string) ($selected['winner_selection_mode'] ?? '') === 'all'
            );
            $this->assert(
                'POST select awards application_id',
                (int) ($selected['awards']['application_id'] ?? 0) === $winner_app_id
            );

            $cleared = $this->runWinnerBuyerController(
                'pb2bFrontendApiBuyerTenderWinnerClearController',
                $tender_id,
                array()
            );
            $this->assert(
                'POST clear mode=none',
                (string) ($cleared['winner_selection_mode'] ?? '') === 'none'
            );

            $positional_code = 0;
            try {
                $this->runWinnerBuyerController(
                    'pb2bFrontendApiBuyerTenderWinnerSelectController',
                    $tender_id,
                    array(
                        'mode' => 'positional',
                        'items' => array(array(
                            'tender_item_id' => (int) $prepared['tender_item_id'],
                            'application_id' => $winner_app_id,
                        )),
                    )
                );
            } catch (waException $e) {
                $positional_code = (int) $e->getCode();
            }
            $this->assert(
                'POST positional without itemized → 409',
                $positional_code === pb2bHttpStatus::CONFLICT,
                'code=' . $positional_code
            );

            $complete_empty_code = 0;
            try {
                $this->runWinnerBuyerController(
                    'pb2bFrontendApiBuyerTenderWinnerCompleteController',
                    $tender_id,
                    array('reason' => 'smoke empty')
                );
            } catch (waException $e) {
                $complete_empty_code = (int) $e->getCode();
            }
            $this->assert(
                'POST complete without winner → 409',
                $complete_empty_code === pb2bHttpStatus::CONFLICT,
                'code=' . $complete_empty_code
            );

            $this->runWinnerBuyerController(
                'pb2bFrontendApiBuyerTenderWinnerSelectController',
                $tender_id,
                array(
                    'mode' => 'all',
                    'application_id' => $winner_app_id,
                )
            );
            $done = $this->runWinnerBuyerController(
                'pb2bFrontendApiBuyerTenderWinnerCompleteController',
                $tender_id,
                array('reason' => 'smoke api complete')
            );
            $this->assert(
                'POST complete → summarizing_results',
                (string) ($done['tender']['status']['code'] ?? '') === pb2bTenderStatus::SUMMARIZING_RESULTS->value,
                (string) ($done['tender']['status']['code'] ?? '')
            );
            $this->assert(
                'POST complete winner.completed=1',
                (int) (($done['winner']['winner_selection_completed'] ?? 0)) === 1
            );

            $select_after_code = 0;
            try {
                $this->runWinnerBuyerController(
                    'pb2bFrontendApiBuyerTenderWinnerSelectController',
                    $tender_id,
                    array(
                        'mode' => 'all',
                        'application_id' => $winner_app_id,
                    )
                );
            } catch (waException $e) {
                $select_after_code = (int) $e->getCode();
            }
            $this->assert(
                'POST select after complete → 409',
                $select_after_code === pb2bHttpStatus::CONFLICT,
                'code=' . $select_after_code
            );

            $other_buyer = (new pb2bCompanyModel())->query(
                'SELECT id FROM pb2b_company
                 WHERE buyer = 1 AND IFNULL(deleted, 0) = 0 AND id <> ?
                 ORDER BY id ASC LIMIT 1',
                (int) $buyer->id
            )->fetchAssoc();
            if (!empty($other_buyer['id'])) {
                $this->winnerApiCompany = new pb2bCompany((int) $other_buyer['id']);
                $foreign_code = 0;
                try {
                    $this->runWinnerBuyerController(
                        'pb2bFrontendApiBuyerTenderWinnerGetController',
                        $tender_id,
                        array()
                    );
                } catch (waException $e) {
                    $foreign_code = (int) $e->getCode();
                }
                $this->assert(
                    'Foreign buyer GET winner → 403/404',
                    in_array($foreign_code, array(
                        pb2bHttpStatus::FORBIDDEN,
                        pb2bHttpStatus::NOT_FOUND,
                    ), true),
                    'code=' . $foreign_code
                );
                $this->winnerApiCompany = $buyer;
            } else {
                $this->out('NOTE: нет второго buyer — skip foreign GET');
            }

            $prepared2 = $this->prepareReviewedWinnerSmokeTender(
                $service,
                $buyer,
                $actor,
                $type_id,
                $suppliers,
                'Smoke API finish-wo'
            );
            $tender_finish = $prepared2['tender'];
            $finish_app_ids = $prepared2['app_ids'];
            $finish_id = (int) $tender_finish->id;

            $empty_reason_code = 0;
            try {
                $this->runWinnerBuyerController(
                    'pb2bFrontendApiBuyerTenderWinnerFinishWithoutController',
                    $finish_id,
                    array('reason' => '   ')
                );
            } catch (waException $e) {
                $empty_reason_code = (int) $e->getCode();
            }
            $this->assert(
                'POST finish-without empty → 400',
                $empty_reason_code === pb2bHttpStatus::BAD_REQUEST,
                'code=' . $empty_reason_code
            );

            $finished = $this->runWinnerBuyerController(
                'pb2bFrontendApiBuyerTenderWinnerFinishWithoutController',
                $finish_id,
                array('reason' => 'smoke api no winner')
            );
            $this->assert(
                'POST finish-without → podvedenie',
                (string) ($finished['tender']['status']['code'] ?? '') === pb2bTenderStatus::SUMMARIZING_RESULTS->value
            );
            $this->assert(
                'POST finish-without flag=1',
                (int) (($finished['winner']['finished_without_winner'] ?? 0)) === 1
            );
        } catch (Exception $e) {
            $this->assert('winner API controllers smoke', false, $e->getMessage());
        }

        $this->winnerApiCompany = null;
        $this->winnerApiActor = null;
        $_POST = array();

        foreach (array_merge($app_ids, $finish_app_ids) as $aid) {
            $this->deleteSmokeApplication((int) $aid);
        }
        foreach (array($tender, $tender_finish) as $t) {
            if ($t && (int) $t->id > 0) {
                try {
                    (new pb2bTenderModel())->updateById((int) $t->id, array('is_deleted' => 1));
                } catch (Exception $e) {
                    $this->out('NOTE: winner API smoke tender cleanup: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * CLI без waAuth: подставляем CabinetContext (компания smoke-сценария).
     *
     * @param array<string, mixed> $post
     * @return array<string, mixed>
     * @throws waException
     */
    private function runWinnerBuyerController(string $class, int $tender_id, array $post): array
    {
        if (!$this->winnerApiCompany || !$this->winnerApiActor) {
            throw new waException('winner API smoke context не задан', pb2bHttpStatus::BAD_REQUEST);
        }

        $_POST = $post;
        waRequest::setParam('role', 'buyer');
        waRequest::setParam('id', $tender_id);

        /** @var pb2bFrontendCabinetController $ctrl */
        $ctrl = new $class();
        $ctx_ref = new ReflectionProperty($ctrl, 'context');
        $ctx_ref->setAccessible(true);
        $ctx_ref->setValue(
            $ctrl,
            new pb2bCabinetContext(
                $this->winnerApiActor,
                $this->winnerApiCompany,
                pb2bCompanyRole::BUYER
            )
        );
        $ctrl->executeBuyer();

        $ref = new ReflectionProperty($ctrl, 'response');
        $ref->setAccessible(true);
        $response = $ref->getValue($ctrl);

        return is_array($response) ? $response : array();
    }

    /**
     * ЗЦ → publish → 2 submitted → end_at past → start-review.
     *
     * @param list<array{id:mixed}> $suppliers
     * @return array{
     *   tender: pb2bTender,
     *   app_ids: list<int>,
     *   cheap_app_id: int,
     *   tender_item_id: int
     * }
     */
    private function prepareReviewedWinnerSmokeTender(
        pb2bTenderService $service,
        pb2bCompany $buyer,
        waContact $actor,
        int $type_id,
        array $suppliers,
        string $title_prefix
    ): array {
        $tender = $service->createFromBuyer(
            (int) $buyer->id,
            new pb2bTenderDto(array(
                'type' => $type_id,
                'title' => $title_prefix . ' ' . date('H:i:s'),
            )),
            $actor
        );
        $service->updateFromBuyer(
            (int) $tender->id,
            (int) $buyer->id,
            new pb2bTenderDto(array(
                'title' => $title_prefix . ' ' . date('H:i:s'),
                'end_at' => date('Y-m-d H:i:s', time() + 3 * 86400),
                'itemized_enabled' => 0,
                'approval_required' => 0,
                'criteria' => array(
                    array(
                        'name' => 'Smoke optional',
                        'type' => 'non_price',
                        'is_mandatory' => 0,
                        'description' => 'CLI',
                    ),
                ),
                'items' => array(
                    array(
                        'name' => 'Smoke lot',
                        'qty' => 10,
                        'unit' => 'шт.',
                        'max_price_no_vat' => 1000,
                        'vat_rate' => '20%',
                        'delivery_place' => 'Москва',
                        'sort' => 0,
                    ),
                ),
            )),
            $actor
        );
        $service->publishFromBuyer((int) $tender->id, (int) $buyer->id, 'smoke winner publish', $actor);
        $tender = new pb2bTender((int) $tender->id);
        $detail = $service->getDetailFromBuyer((int) $tender->id, $buyer);
        $tender_item_id = (int) ($detail['items'][0]['id'] ?? 0);
        if ($tender_item_id <= 0) {
            throw new waException('winner smoke: нет позиции извещения');
        }

        $prices = array(40.0, 90.0);
        $app_ids = array();
        $cheap_app_id = 0;
        for ($i = 0; $i < 2; $i++) {
            $aid = $this->createSubmittedSmokeApplication(
                (int) $tender->id,
                (int) $suppliers[$i]['id'],
                $tender_item_id,
                10,
                $prices[$i],
                'pending'
            );
            $app_ids[] = $aid;
            if ($i === 0) {
                $cheap_app_id = $aid;
            }
        }

        (new pb2bTenderModel())->updateById((int) $tender->id, array(
            'end_at' => date('Y-m-d H:i:s', time() - 60),
        ));
        $service->startReviewForBuyer(
            (int) $tender->id,
            (int) $buyer->id,
            'smoke winner start-review',
            $actor
        );
        $tender = new pb2bTender((int) $tender->id);

        return array(
            'tender' => $tender,
            'app_ids' => $app_ids,
            'cheap_app_id' => $cheap_app_id,
            'tender_item_id' => $tender_item_id,
        );
    }

    /**
     * @param list<array<string, mixed>> $apps
     */
    private function rankingHasRankForTotal(array $apps, float $total, int $rank): bool
    {
        foreach ($apps as $row) {
            if (!is_array($row)) {
                continue;
            }
            if (abs((float) ($row['total'] ?? 0) - $total) < 0.001
                && (int) ($row['rank'] ?? 0) === $rank
            ) {
                return true;
            }
        }

        return false;
    }

    private function createSubmittedSmokeApplication(
        int $tender_id,
        int $supplier_id,
        int $tender_item_id,
        float $qty,
        float $price,
        string $admission_status
    ): int {
        $application = new pb2bTenderApplication();
        $save = $application->save(array(
            'tender_id' => $tender_id,
            'supplier_company_id' => $supplier_id,
            'status' => pb2bTenderApplication::STATUS_DRAFT,
            'nonprice_done' => 1,
            'approval_status' => 'not_required',
            'qualification_status' => 'not_required',
            'admission_status' => 'pending',
        ));
        if (!empty($save['error']) || (int) $application->id <= 0) {
            $this->assert(
                'Create draft smoke application',
                false,
                (string) ($save['message'] ?? 'fail')
            );
            return 0;
        }

        $item = new pb2bTenderApplicationItem();
        $item_save = $item->save(array(
            'application_id' => (int) $application->id,
            'tender_item_id' => $tender_item_id,
            'qty' => $qty,
            'unit' => 'шт.',
            'price_per_unit' => $price,
            'vat_rate' => '20%',
            'sort' => 0,
        ));
        if (!empty($item_save['error']) || (int) $item->id <= 0) {
            $this->assert(
                'Create smoke application item price=' . $price,
                false,
                (string) ($item_save['message'] ?? '')
            );
            return 0;
        }

        $submit = $application->applySubmit();
        if (!empty($submit['error'])) {
            $this->assert(
                'Submit smoke application',
                false,
                (string) ($submit['message'] ?? '')
            );
            return 0;
        }

        if ($admission_status !== 'pending') {
            $data = is_array($application->data) ? $application->data : array();
            unset($data['create_datetime'], $data['update_datetime']);
            $data['admission_status'] = $admission_status;
            $adm = $application->save($data);
            if (!empty($adm['error'])) {
                $this->assert(
                    'Set admission=' . $admission_status,
                    false,
                    (string) ($adm['message'] ?? '')
                );
                return 0;
            }
        }

        $this->assert('Smoke application submitted id=' . (int) $application->id, true);

        return (int) $application->id;
    }

    /**
     * @param array{items?: list<array<string, mixed>>} $detail
     */
    private function persistSmokeApplication(pb2bTender $tender, array $detail, int $organizer_id): int
    {
        $supplier_row = (new pb2bCompanyModel())->query(
            'SELECT id FROM pb2b_company WHERE supplier = 1 AND IFNULL(deleted, 0) = 0 AND id <> ? ORDER BY id ASC LIMIT 1',
            $organizer_id
        )->fetchAssoc();
        $supplier_id = (int) ($supplier_row['id'] ?? 0);
        if ($supplier_id <= 0) {
            $this->assert('Supplier company for application persist', false, 'нет другой компании');
            return 0;
        }

        $tender_item_id = (int) ($detail['items'][0]['id'] ?? 0);
        if ($tender_item_id <= 0) {
            $this->assert('Tender item id for application persist', false);
            return 0;
        }

        $criterion_id = (int) ($detail['criteria'][0]['id'] ?? 0);
        if ($criterion_id <= 0) {
            $this->assert('Tender criterion id for application flow', false);
            return 0;
        }

        $application_service = new pb2bTenderApplicationService();
        $draft = $application_service->getOrCreateDraft((int) $tender->id, $supplier_id);
        $application_id = (int) ($draft['application']['id'] ?? 0);
        $application = new pb2bTenderApplication($application_id);
        $this->assert('Persist tender application draft', $application_id > 0);
        $this->assert(
            'Draft status is draft',
            $application->getStatusCode() === pb2bTenderApplication::STATUS_DRAFT
        );

        $buyer_list = $application_service->getForBuyer((int) $tender->id, $organizer_id);
        $draft_visible = false;
        foreach ((array) ($buyer_list['applications'] ?? array()) as $row) {
            if ((int) ($row['id'] ?? 0) === $application_id
                && (string) ($row['status']['code'] ?? '') === pb2bTenderApplication::STATUS_DRAFT
            ) {
                $draft_visible = true;
                break;
            }
        }
        $this->assert('Buyer sees started draft participation', $draft_visible);

        $price_blocked = false;
        try {
            $application_service->save((int) $tender->id, $supplier_id, array(
                'items' => array(array(
                    'tender_item_id' => $tender_item_id,
                    'price_per_unit' => 55.5,
                )),
            ));
        } catch (waException $e) {
            $price_blocked = (int) $e->getCode() === pb2bHttpStatus::CONFLICT;
        }
        $this->assert('Price save blocked before approval', $price_blocked);

        $saved_nonprice = $application_service->save((int) $tender->id, $supplier_id, array(
            'criteria' => array(array(
                'criterion_id' => $criterion_id,
                'value' => 'Smoke подтверждение',
                'confirmed' => 1,
            )),
        ));
        $this->assert('Mandatory nonprice completed', !empty($saved_nonprice['gates']['nonprice_done']));

        $approved = $application_service->decideForBuyer(
            (int) $tender->id,
            $organizer_id,
            $application_id,
            array('approval_status' => 'approved')
        );
        $this->assert(
            'Buyer approves draft before submit',
            (string) ($approved['application']['approval_status']['code'] ?? '') === 'approved'
        );

        $saved_price = $application_service->save((int) $tender->id, $supplier_id, array(
            'items' => array(array(
                'tender_item_id' => $tender_item_id,
                'qty' => $detail['items'][0]['qty'] ?? 2,
                'unit' => $detail['items'][0]['unit'] ?? 'шт.',
                'price_per_unit' => 55.5,
                'vat_rate' => '20%',
                'sort' => 0,
            )),
        ));
        $this->assert('Persist application price after approval', !empty($saved_price['application']['items']));

        $submitted = $application_service->submit((int) $tender->id, $supplier_id);
        $this->assert(
            'Application becomes submitted only after proposal send',
            (string) ($submitted['application']['status']['code'] ?? '') === pb2bTenderApplication::STATUS_SUBMITTED
        );

        $admitted = $application_service->decideForBuyer(
            (int) $tender->id,
            $organizer_id,
            $application_id,
            array('admission_status' => 'admitted')
        );
        $this->assert(
            'Admission allowed after submit',
            (string) ($admitted['application']['admission_status']['code'] ?? '') === 'admitted'
        );

        $row = (new waModel())->query(
            'SELECT a.status, i.price_per_unit
             FROM pb2b_tender_application a
             INNER JOIN pb2b_tender_application_item i ON i.application_id = a.id
             WHERE a.id = ?',
            $application_id
        )->fetchAssoc();
        $this->assert(
            'DB has submitted proposal and price',
            ($row['status'] ?? '') === 'submitted' && (float) ($row['price_per_unit'] ?? 0) === 55.5,
            (string) ($row['status'] ?? '') . '/' . (string) ($row['price_per_unit'] ?? '')
        );

        return $application_id;
    }

    private function deleteSmokeApplication(int $application_id): void
    {
        if ($application_id <= 0) {
            return;
        }
        try {
            (new pb2bTenderApplicationItemModel())->deleteByField('application_id', $application_id);
            (new pb2bTenderApplicationDocumentModel())->deleteByField('application_id', $application_id);
            (new pb2bTenderApplicationCriterionModel())->deleteByField('application_id', $application_id);
            (new pb2bTenderApplicationModel())->deleteById($application_id);
        } catch (Exception $e) {
            $this->out('NOTE: не удалось удалить smoke-заявку: ' . $e->getMessage());
        }
    }

    private function assert(string $name, bool $ok, string $detail = ''): void
    {
        if ($ok) {
            $this->passed++;
            $this->out("[OK] {$name}");
        } else {
            $this->failed++;
            $this->out("[FAIL] {$name}" . ($detail ? " — {$detail}" : ''));
        }
    }

    private function out(string $line): void
    {
        echo $line . PHP_EOL;
    }
}
