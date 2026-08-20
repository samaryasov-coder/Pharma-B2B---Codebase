<?php

class pb2bBackendSetSidebarModeController extends waJsonController
{
    /**
     * @return void
     * @throws waException
     */
    public function execute(): void
    {
		$mode = waRequest::post('mode', '');
		$this->response = pb2bWaproHelper::setSidebarMode($mode);
    }
}