<?php
readonly class pb2bCompanyDto extends pb2bBaseDto
{
    public string $name;
    public string $inn;
    public string $kpp;
    public string $ogrn;
    public string $company_type;
    public ?string $type_organization;
    public bool $buyer;
    public bool $supplier;
    public string $registry_email;
    public string $phone;
    public string $contact_person;
    public string $site;
    public string $jur_address;
}
