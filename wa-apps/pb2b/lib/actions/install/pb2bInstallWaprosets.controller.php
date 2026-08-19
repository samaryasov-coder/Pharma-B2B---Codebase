<?php

class pb2bInstallWaprosetsController extends waJsonController{
    public function execute()
    {
        $waproSetsInstall = new waproSetsInstall();
        $this->response = $waproSetsInstall->install();
    }
}    }
}