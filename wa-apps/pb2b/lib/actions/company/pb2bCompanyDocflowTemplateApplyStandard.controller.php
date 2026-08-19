<?php

class pb2bCompanyDocflowTemplateApplyStandardController extends waJsonController
{
    public function execute(): void
    {
        $company = new pb2bCompany(waRequest::post('company_id', null, waRequest::TYPE_INT));
        if (!$company->id) {
            $this->response = array('error' => true, 'message' => 'Компания не найдена');
            return;
        }
        $scope = waRequest::post('scope', 'all', waRequest::TYPE_STRING_TRIM);
        $process_type = waRequest::post('process_type', 1, waRequest::TYPE_INT);
        $this->response = pb2bDocflowDefaults::applyToBuyerTemplate($company, $process_type, $scope);
    }
}
