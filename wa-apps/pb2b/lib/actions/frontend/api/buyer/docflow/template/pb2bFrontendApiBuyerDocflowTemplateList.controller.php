<?php
class pb2bFrontendApiBuyerDocflowTemplateListController extends pb2bFrontendCabinetController {
    public function executeAction()
    {
        $company_type = waRequest::get('type', 0, waRequest::TYPE_STRING_TRIM);
        $company_id = $this->context->company()->id;
        $process_type = 1;

        $collection = new pb2bDocflowTemplateCollection("itemsWithFiles.company_id=$company_id&itemsWithFiles.company_type=$company_type&itemsWithFiles.process_type=$process_type");
        $rows = $collection->getCollection([
            'key' => false,
            'select' => [
                'comment' => null,
                ['field' => 'id', 'table' => 'DTI', 'as' => 'id'],
                ['field' => 'name', 'table' => 'DTI', 'as' => 'name'],
                ['field' => 'filename', 'table' => 'FL', 'as' => 'filename'],
            ],
        ]);

        $this->response = $rows;
    }
}