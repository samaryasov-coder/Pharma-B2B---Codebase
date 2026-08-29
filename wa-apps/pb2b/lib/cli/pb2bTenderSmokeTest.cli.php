<?php

/**
 * Smoke для тендеров.
 * Кабинетный Company/wizard-путь снят (этап 1 сервиса ЗЦ).
 * Полный create→criteria→publish вернётся после pb2bTenderService.
 */
class pb2bTenderSmokeTestCli extends waCliController
{
    private int $passed = 0;
    private int $failed = 0;

    public function execute()
    {
        $this->out('=== Tender smoke test (CLI, post-Company cleanup) ===');

        $this->testDbTables();
        $this->testNoProcedureLegacy();
        $this->testCompanyFacadeRemoved();
        $this->testObjectPersistHelpers();

        $this->out('');
        $this->out('NOTE: create/save/publish smoke ждёт pb2bTenderService (следующий этап).');
        $this->out("PASSED: {$this->passed}, FAILED: {$this->failed}");
        exit($this->failed > 0 ? 1 : 0);
    }

    private function testDbTables(): void
    {
        $model = new waModel();
        foreach (array('pb2b_tender', 'pb2b_tender_classifier', 'pb2b_tender_state_log', 'pb2b_invitation', 'pb2b_criterion') as $table) {
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
