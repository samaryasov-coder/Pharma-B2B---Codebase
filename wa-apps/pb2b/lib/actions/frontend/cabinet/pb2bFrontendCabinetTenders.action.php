<?php
class pb2bFrontendCabinetTendersAction extends pb2bFrontendCabinetAction
{
    public function executeBuyer()
    {
        $type_filters = array();
        foreach ((array) pb2bWaproHelper::getConfigOption('tender_types') as $type) {
            if (empty($type['code']) || (string) ($type['code'] ?? '') === 'auction') {
                continue;
            }
            $type_filters[] = array(
                'code' => (string) $type['code'],
                'label' => (string) ($type['modal_name'] ?? $type['name'] ?? $type['code']),
            );
        }
        $this->view->assign('tender_type_filters', $type_filters);
        $this->setThemeTemplate('html/cabinet/buyer/tenders.html');
    }

    public function executeSupplier()
    {
        $this->setThemeTemplate('html/cabinet/supplier/tenders.html');
    }
}
