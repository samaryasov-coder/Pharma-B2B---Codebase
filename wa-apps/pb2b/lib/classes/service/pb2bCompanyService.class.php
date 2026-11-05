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
        if (!$contact->exists()) {
            throw new waException('Пользователь не найден, необходима авторизация', pb2bHttpStatus::BAD_REQUEST);
        }

        $company = new pb2bCompany();
        $data = $company_dto->toArray();
        $data['contact_id'] = $contact_id;
        $result_save = $company->save($data);
        if (!empty($result_save['error'])) {
            throw new waException((string) ($result_save['message'] ?? 'Не удалось создать компанию'), pb2bHttpStatus::BAD_REQUEST);
        }
        return $company;
    }
}
