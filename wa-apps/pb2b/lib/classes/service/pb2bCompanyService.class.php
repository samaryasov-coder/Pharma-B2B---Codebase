<?php
class pb2bCompanyService
{
    protected pb2bCompanyModel $companyModel;

    public function __construct(){
        $this->companyModel = new pb2bCompanyModel();
    }

    public function createCompany(pb2bCompanyDto $company_dto, int $contact_id): pb2bCompany
    {
        $contact = new waContact($contact_id);
        if (!$contact->exists())
            throw new waException('Пользователь не найден, необходима авторизация', pb2bHttpStatus::BAD_REQUEST);

        $company = new pb2bCompany();
        $result_save = $company->save($company_dto->toArray());
        waLog::log($result_save, 'lol.log');
        return $company;
    }
}