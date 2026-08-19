<?php
class pb2bCabinetContext
{
    public function __construct(
        private ?waContact $contact,
        private ?pb2bCompany $company,
        private ?pb2bCompanyRole $role,
    ) {}

    public function contact(): ?waContact
    {
        return $this->contact;
    }

    public function company(): ?pb2bCompany
    {
        return $this->company;
    }

    public function role(): ?pb2bCompanyRole
    {
        return $this->role;
    }
}