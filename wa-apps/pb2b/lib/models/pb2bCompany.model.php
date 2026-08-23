<?php

class pb2bCompanyModel extends pb2bWaproModel
{
    protected $table = 'pb2b_company';

    public function getByContact($contact_id): ?pb2bCompany
    {
        $data = $this->getByField('contact_id', $contact_id);
        $company = new pb2bCompany($data['id'] ?? null);
        return $company['id'] ? $company :  null;
    }
}