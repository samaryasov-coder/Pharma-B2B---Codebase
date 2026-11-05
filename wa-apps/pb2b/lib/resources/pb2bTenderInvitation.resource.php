<?php

class pb2bTenderInvitationResource extends pb2bBaseJsonResource
{
    public function toArray(): array
    {
        $row = $this->resource;
        $company_name = '';
        if (!empty($row['company_id'])) {
            $company = new pb2bCompany();
            $company->setField('name', $row['company_name']);
            $company->setField('company_type', $row['company_type']);
            $company->setField('type_organization', $row['company_organization_type']);
            $company_name = $company->getFullName();
        }

        return array(
            'id' => $row['id'],
            'tender_id' => $row['tender_id'],
            'supplier_company_id' => $row['supplier_company_id'],
            'invited_by_contact_id' => $row['invited_by_contact_id'],
            'status' => $row['status'],
            'sent_at' => $row['sent_at'],
            'responded_at' => $row['responded_at'],
            'company_name' => $company_name,
        );
    }
}
