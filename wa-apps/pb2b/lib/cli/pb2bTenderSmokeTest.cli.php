<?php

class pb2bTenderSmokeTestCli extends waCliController
{
    private int $passed = 0;
    private int $failed = 0;

    public function execute()
    {
        $this->out('=== Tender smoke test (CLI, no HTTP) ===');

        $buyer_company_id = 1;
        $buyer_contact_id = 48;
        $non_buyer_company_id = 4;

        $this->testDbTables();
        $this->testNoProcedureLegacy();

        $company = new pb2bCompany($buyer_company_id);
        $this->assert('Buyer company exists', $company->id > 0);
        $this->assert('Buyer flag', $company->isBuyer());

        $non_buyer = new pb2bCompany($non_buyer_company_id);
        $this->assert('Non-buyer tenderAssertBuyer fails', !empty($non_buyer->tenderAssertBuyer()['error']));

        wa()->setUser(new waAuthUser(new waContact($buyer_contact_id)));

        $save = $company->tenderSaveWizardFromBuyer('basic', array(
            'type' => 3,
            'title' => 'Smoke test ' . date('Y-m-d H:i:s'),
            'number' => 'SMOKE-' . time(),
            'responsible_contact_id' => $buyer_contact_id,
        ), 0);
        $this->assert('Save new tender', empty($save['error']), $save['message'] ?? '');
        $tender_id = (int) ($save['tender_id'] ?? 0);
        $this->assert('Draft status', (int) ($save['status'] ?? 0) === 1);

        $crit = $company->tenderReplaceCriteriaFromBuyer($tender_id, array(
            array('name' => 'Smoke criterion', 'type' => 'non_price'),
        ));
        $this->assert('Criterion save', empty($crit['error']), $crit['message'] ?? '');

        $row_check = (new pb2bTenderModel())->getById($tender_id);
        $this->assert('Tender row in DB after save', !empty($row_check['id']));

        $tender_obj = new pb2bTender($tender_id);
        $this->assert('pb2bTender loads by id', (int) ($tender_obj->id ?? 0) === $tender_id);

        $publish = (new pb2bTender($tender_id))->publish();
        $this->assert(
            'Publish to opublikovan',
            empty($publish['error']) && (int) ($publish['to_status'] ?? 0) === 3,
            $publish['message'] ?? ''
        );

        $save2 = $company->tenderSaveWizardFromBuyer('basic', array(
            'type' => 3,
            'is_private' => 1,
            'title' => 'Smoke closed',
            'number' => 'SMOKE-CLOSED-' . time(),
            'responsible_contact_id' => $buyer_contact_id,
        ), 0);
        $closed_id = (int) ($save2['tender_id'] ?? 0);
        $pub_closed = $company->tenderPublishFromBuyer($closed_id);
        $this->assert('Closed without invitations blocked', !empty($pub_closed['error']));

        $supplier_id = (int) (new pb2bCompanyModel())->select('id')->where('supplier = 1')->order('id ASC')->limit(1)->fetchField('id');
        if ($supplier_id > 0 && $closed_id > 0) {
            $inv = $company->tenderReplaceInvitationsFromBuyer($closed_id, array($supplier_id));
            $this->assert('Invitation save', empty($inv['error']), $inv['message'] ?? '');
            $crit_closed = $company->tenderReplaceCriteriaFromBuyer($closed_id, array(
                array('name' => 'Smoke closed criterion', 'type' => 'non_price'),
            ));
            $this->assert('Closed criterion save', empty($crit_closed['error']));
            $pub_closed_ok = $company->tenderPublishFromBuyer($closed_id);
            $this->assert(
                'Closed with invitations publishes',
                empty($pub_closed_ok['error']) && (int) ($pub_closed_ok['to_status'] ?? $pub_closed_ok['status'] ?? 0) === 3,
                $pub_closed_ok['message'] ?? ''
            );
        } else {
            $this->out('SKIP: no supplier company for invitation test');
        }

        $save_pq_closed = $company->tenderSaveWizardFromBuyer('privacy', array(
            'type' => 1,
            'is_private' => 1,
            'title' => 'Smoke PQ closed',
            'number' => 'SMOKE-PQ-' . time(),
            'responsible_contact_id' => $buyer_contact_id,
            'invitations' => $supplier_id > 0 ? array($supplier_id) : array(),
        ), 0);
        $pq_id = (int) ($save_pq_closed['tender_id'] ?? 0);
        if ($supplier_id > 0 && $pq_id > 0) {
            $pq_save_params = $company->tenderSaveWizardFromBuyer('purchase_params', array(
                'type' => 1,
                'prequal_validity_months' => 12,
            ), $pq_id);
            $this->assert('Prequal months save', empty($pq_save_params['error']), $pq_save_params['message'] ?? '');
            $inv_count = pb2bTender::countInvitationsForTender($pq_id);
            $this->assert('Prequal privacy invitations', (int) ($inv_count['count'] ?? 0) >= 1);
        }

        $save_pq = $company->tenderSaveWizardFromBuyer('purchase_params', array(
            'type' => 1,
            'retendering_enabled' => 1,
        ), $tender_id);
        $this->assert('Prequal retendering rejected', !empty($save_pq['error']));

        $esklp_id = (int) (new pb2bEsklpModel())->select('id')->order('id ASC')->limit(1)->fetchField('id');
        if ($esklp_id > 0) {
            $cls = $company->tenderReplaceClassifiersFromBuyer($tender_id, array(
                array('classifier_type' => 1, 'classifier_id' => $esklp_id),
            ));
            $this->assert('Classifier save', empty($cls['error']), $cls['message'] ?? '');
            $row = (new pb2bTenderClassifierModel())->getByField('tender_id', $tender_id);
            $this->assert('Classifier in DB', !empty($row));
        } else {
            $this->out('SKIP: no pb2b_esklp rows');
        }

        $get = $company->tenderGetWithClassifiers($tender_id);
        $this->assert('Get with classifiers', empty($get['error']) && !empty($get['tender']));

        $list = (new pb2bTenderCollection())->getBuyerList($buyer_company_id, array());
        $this->assert('Buyer list', is_array($list) && count($list) > 0);

        $this->out('');
        $this->out("PASSED: {$this->passed}, FAILED: {$this->failed}");
        exit($this->failed > 0 ? 1 : 0);
    }

    private function testDbTables(): void
    {
        $model = new waModel();
        foreach (array('pb2b_tender', 'pb2b_tender_classifier', 'pb2b_tender_state_log', 'pb2b_invitation', 'pb2b_criterion') as $table) {
            $exists = $model->query("SHOW TABLES LIKE ?", $table)->fetch();
            $this->assert("Table {$table}", !empty($exists));
        }
    }

    private function testNoProcedureLegacy(): void
    {
        $dir = wa()->getAppPath('lib', 'pb2b');
        $grep = shell_exec('grep -r --exclude="*SmokeTest*" pb2bProcedure ' . escapeshellarg($dir) . ' 2>/dev/null') ?: '';
        $this->assert('No pb2bProcedure in pb2b', trim($grep) === '');
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
