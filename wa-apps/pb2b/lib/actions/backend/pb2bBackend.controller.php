<?php
class pb2bBackendController extends waViewController
{
    public function execute(): void
    {
        $this->executeAction(new pb2bBackendStartAction());
    }
}