<?php
class pb2bFrontendCabinetDataFormAction extends pb2bFrontendCabinetFormAction
{
    protected $map = [
        'logo' => 'logo',
        'jurInfo' => 'jurInfo',
        'contactInfo' => 'contactInfo',
        'contactPerson' => 'contactPerson',
        'responsiblePerson' => 'responsiblePerson',
    ];

    public function execute()
    {
        $this->handle('cabinet.data.edit');
    }
}