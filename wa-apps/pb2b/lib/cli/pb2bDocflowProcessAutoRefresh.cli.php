<?php

class pb2bDocflowProcessAutoRefreshCli extends waCliController
{
    public function execute()
    {
        $collection = new pb2bDocflowRequestCollection();
        $collection->processAutoRefresh();
    }
}
