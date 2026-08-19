<?php
class pb2bCompanyCategoryService extends pb2bWaproService
{
    
    public function __construct()
    {
        $this->relationModel = new pb2bCompanyCategoryModel();
    }
}