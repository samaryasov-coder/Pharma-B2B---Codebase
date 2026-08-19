<?php

class pb2bDocflowRequestItemPolicy
{
    public static function downloadReviewerFile(pb2bDocflowRequestItem $request_item, pb2bCompany $company): bool
    {
        $request = $request_item->getRequest();
        return pb2bDocflowRequestPolicy::view($request, $company);
    }

    public static function uploadFromProvider(pb2bDocflowRequestItem $request_item, pb2bCompany $company): bool
    {
        $request = $request_item->getRequest();
        return $request->getProviderCompany()?->id == $company->id;
    }
}