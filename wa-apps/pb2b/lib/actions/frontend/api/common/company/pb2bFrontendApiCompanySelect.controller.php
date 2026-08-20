<?php
class pb2bFrontendApiCompanySelectController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $search = waRequest::get('search', '', waRequest::TYPE_STRING_TRIM);
        $is_supplier = waRequest::get('supplier', null, waRequest::TYPE_INT);

        $hash = '';
        if ($search !== '')
            $hash .= "&name~=$search";
        if (!is_null($is_supplier))
            $hash .= "&supplier=" . !empty($is_supplier);

        $company_id = $this->context->company()->id;
        $collection = new pb2bCompanyCollection("id<>$company_id&$hash");
        $rows = $collection->getCollection([
            'key' => false,
            'select' => [
                'id' => null,
                'name' => 'text',
                'company_type' => null,
                'order' => ['name' => 'ASC'],
            ]
        ]);

        foreach ($rows as &$row) {
            $company = new pb2bCompany($row['id']);
            $row['text'] = $company->getFullName();
        }

        $this->response = [ 'results' => $rows];
    }
}