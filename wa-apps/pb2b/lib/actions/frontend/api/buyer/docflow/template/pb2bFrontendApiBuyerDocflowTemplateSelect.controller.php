<?php
class pb2bFrontendApiBuyerDocflowTemplateSelectController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $search = waRequest::get('search', '', waRequest::TYPE_STRING_TRIM);
        $type = waRequest::get('type', '', waRequest::TYPE_STRING_TRIM);
        $process_type = 1;

        $hash = '';
        if (!empty($search))
            $hash = "&items.text~=$search";
        if (!is_null($type))
            $hash .= "&items.company_type=$type";

        $company_id = $this->context->company()->id;
        $collection = new pb2bDocflowTemplateCollection("items.company_id=$company_id&items.process_type=$process_type$hash");

        $rows = $collection->getCollection([
            'key' => false,
            'select' => [
                ['field' => 'id', 'table' => 'DTI', 'as' => 'id'],
                ['field' => 'name', 'table' => 'DTI', 'as' => 'text'],
                ['field' => 'company_type', 'table' => 'DTI', 'as' => 'company_type'],
            ],
            'order' => [
                'name' => ['table' => 'DTI', 'dir' => 'ASC'],
            ],
        ]);

        $this->response = [ 'results' => $rows];
    }
}