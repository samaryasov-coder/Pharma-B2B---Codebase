<?php

class pb2bBackendStartAction extends waViewAction
{
    public function execute(): void
    {
        $this->setLayout(new pb2bBackendLayout());
    }
}