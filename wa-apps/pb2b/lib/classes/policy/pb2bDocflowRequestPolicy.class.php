<?php

class pb2bDocflowRequestPolicy
{
    public static function view(pb2bDocflowRequest $request, pb2bCompany $company): bool
    {
        return ($request->getReviewerCompany()?->id == $company->id) || ($request->getProviderCompany()?->id == $company->id);
    }

    public static function approveFromReviewer(pb2bDocflowRequest $request, pb2bCompany $company): bool
    {
        return $request->getReviewerCompany()?->id == $company->id;
    }

    public static function cancelFromReviewer(pb2bDocflowRequest $request, pb2bCompany $company): bool
    {
        return $request->getReviewerCompany()?->id == $company->id;
    }

    public static function submitFromProvider(pb2bDocflowRequest $request, pb2bCompany $company): bool
    {
        return $request->getProviderCompany()?->id == $company->id;
    }

    public static function revokeFromProvider(pb2bDocflowRequest $request, pb2bCompany $company): bool
    {
        return $request->getProviderCompany()?->id == $company->id;
    }
}