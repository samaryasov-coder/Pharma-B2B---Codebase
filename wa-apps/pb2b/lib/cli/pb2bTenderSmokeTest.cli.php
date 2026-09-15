<?php

/**
 * Smoke для тендеров ЗЦ: таблицы, фасады, create→items→publish → priem_zayavok,
 * persist черновика заявки и строки цены.
 */
class pb2bTenderSmokeTestCli extends waCliController
{
    private int $passed = 0;
    private int $failed = 0;

    public function execute()
    {
        $this->out('=== Tender smoke test (CLI) ===');

        $this->testDbTables();
        $this->testNoProcedureLegacy();
        $this->testCompanyFacadeRemoved();
        $this->testObjectPersistHelpers();
        $this->testCreateItemsPublishAndApplicationPersist();

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
            'pb2b_invitation',
            'pb2b_criterion',
            'pb2b_tender_item',
            'pb2b_tender_document',
            'pb2b_application',
            'pb2b_application_item',
            'pb2b_application_document',
            'pb2b_application_criterion',
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
        $this->assert('MVP types include price_request', pb2bTender::isMvpTypeCode('price_request'));
        $methods = pb2bTender::getCreateModalMethods();
        $this->assert('Create modal methods is array', is_array($methods) && count($methods) > 0);

        $codes = array();
        foreach ($methods as $row) {
            if (!empty($row['code'])) {
                $codes[] = (string) $row['code'];
            }
        }
        $this->assert('Modal lists price_request', in_array('price_request', $codes, true));
        $this->assert('TenderApplication draft is allowed', pb2bTenderApplication::isAllowedStatus('draft'));
        $this->assert('TenderApplication bogus status rejected', !pb2bTenderApplication::isAllowedStatus('opublikovan'));
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
            !pb2bTenderPolicy::viewAsSupplier($empty_tender, $empty_company)
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
            'Buyer prices hidden in priem_zayavok',
            !pb2bTenderApplicationResource::isBuyerPriceVisible('priem_zayavok')
        );
        $this->assert(
            'Buyer prices visible after vskrytie',
            pb2bTenderApplicationResource::isBuyerPriceVisible('vskrytie_zayavok')
        );
        $this->assert(
            'TenderApplicationService has submit',
            method_exists('pb2bTenderApplicationService', 'submit')
        );
        $this->assert(
            'TenderApplicationService has getForBuyer',
            method_exists('pb2bTenderApplicationService', 'getForBuyer')
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

        $types = (array) pb2bWaproHelper::getConfigOption('tender_types', 'code');
        $type_id = (int) ($types['price_request']['id'] ?? 3);
        $number = 'SMOKE-ZC-' . date('YmdHis');
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
                    'number' => $number,
                )),
                $actor
            );
            $this->assert('Create price_request draft', (int) $tender->id > 0);

            $service->updateFromBuyer(
                (int) $tender->id,
                (int) $company->id,
                new pb2bTenderDto(array(
                    'title' => $title,
                    'number' => $number,
                    'end_at' => date('Y-m-d H:i:s', time() + 7 * 86400),
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
                ))
            );

            $detail = $service->getDetailFromBuyer((int) $tender->id, (int) $company->id);
            $this->assert(
                'GET returns saved item',
                !empty($detail['items'][0]['name']) && $detail['items'][0]['name'] === 'Smoke позиция'
            );

            $published = $service->publishFromBuyer((int) $tender->id, (int) $company->id, 'smoke', $actor);
            $statuses = (array) pb2bWaproHelper::getConfigOption('tender_statuses', 'id');
            $status_code = (string) ($statuses[(int) ($published->data['status'] ?? 0)]['code'] ?? '');
            $this->assert('Publish status is priem_zayavok', $status_code === 'priem_zayavok', $status_code);

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
     * @param array{items?: list<array<string, mixed>>} $detail
     */
    private function persistSmokeApplication(pb2bTender $tender, array $detail, int $organizer_id): int
    {
        $supplier_row = (new pb2bCompanyModel())->query(
            'SELECT id FROM pb2b_company WHERE supplier = 1 AND IFNULL(deleted, 0) = 0 AND id <> ? ORDER BY id ASC LIMIT 1',
            $organizer_id
        )->fetchAssoc();
        if (empty($supplier_row['id'])) {
            $supplier_row = (new pb2bCompanyModel())->query(
                'SELECT id FROM pb2b_company WHERE IFNULL(deleted, 0) = 0 AND id <> ? ORDER BY id ASC LIMIT 1',
                $organizer_id
            )->fetchAssoc();
        }
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

        $application = new pb2bTenderApplication();
        $save = $application->save(array(
            'tender_id' => (int) $tender->id,
            'supplier_company_id' => $supplier_id,
        ));
        $this->assert(
            'Persist tender application draft',
            empty($save['error']) && (int) $application->id > 0,
            (string) ($save['message'] ?? '')
        );
        if (!empty($save['error']) || (int) $application->id <= 0) {
            return 0;
        }

        $this->assert('Draft canSubmit', $application->canSubmit());
        $this->assert(
            'Draft status is draft',
            $application->getStatusCode() === pb2bTenderApplication::STATUS_DRAFT
        );

        $bad = (new pb2bTenderApplicationItem())->save(array(
            'application_id' => (int) $application->id,
            'tender_item_id' => $tender_item_id,
            'qty' => 2,
            'unit' => 'шт.',
            'price_per_unit' => 0,
        ));
        $this->assert('Zero price rejected', !empty($bad['error']));

        $item = new pb2bTenderApplicationItem();
        $item_save = $item->save(array(
            'application_id' => (int) $application->id,
            'tender_item_id' => $tender_item_id,
            'qty' => $detail['items'][0]['qty'] ?? 2,
            'unit' => $detail['items'][0]['unit'] ?? 'шт.',
            'price_per_unit' => 55.5,
            'vat_rate' => '20%',
            'sort' => 0,
        ));
        $this->assert(
            'Persist application price row',
            empty($item_save['error']) && (int) $item->id > 0,
            (string) ($item_save['message'] ?? '')
        );

        $row = (new waModel())->query(
            'SELECT a.status, i.price_per_unit
             FROM pb2b_application a
             INNER JOIN pb2b_application_item i ON i.application_id = a.id
             WHERE a.id = ?',
            (int) $application->id
        )->fetchAssoc();
        $this->assert(
            'DB has draft and price',
            ($row['status'] ?? '') === 'draft' && (float) ($row['price_per_unit'] ?? 0) === 55.5,
            (string) ($row['status'] ?? '') . '/' . (string) ($row['price_per_unit'] ?? '')
        );

        return (int) $application->id;
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
