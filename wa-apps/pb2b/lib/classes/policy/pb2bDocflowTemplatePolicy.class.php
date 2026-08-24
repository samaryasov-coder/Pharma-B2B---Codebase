<?php

class pb2bDocflowTemplatePolicy
{
    public static function view(pb2bDocflowTemplate $template, pb2bCompany $company): bool
    {
        return $template->getCompany()->id == $company->id;
    }
}