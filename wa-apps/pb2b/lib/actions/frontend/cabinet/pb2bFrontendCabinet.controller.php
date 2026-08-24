<?php
class pb2bFrontendCabinetController extends pb2bFrontendController
{
    use pb2bFrontendCabinetTrait;

    public function __construct()
    {
        $this->initContext();
    }
}