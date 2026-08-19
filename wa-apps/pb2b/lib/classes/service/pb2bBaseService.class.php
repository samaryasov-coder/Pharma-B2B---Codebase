<?php
class pb2bBaseService
{
    protected function getCompanyWithAssert(int $company_id): pb2bCompany
    {
        $company = new pb2bCompany($company_id);
        if (!$company->id)
            throw new waException('Неизвестная компания', pb2bHttpStatus::NOT_FOUND);

        return $company;
    }
}