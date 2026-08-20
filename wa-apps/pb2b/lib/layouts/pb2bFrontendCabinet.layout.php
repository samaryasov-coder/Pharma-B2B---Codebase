<?php
class pb2bFrontendCabinetLayout extends waLayout
{
    protected $contact;
    protected $company;
    protected $role;

    public function __construct($contact, $company, $role)
    {
        parent::__construct();

        $this->contact = $contact;
        $this->company = $company;
        $this->role = $role;
    }

    public function execute()
    {
        $this->setThemeTemplate('html/cabinet/layout.html');

        wa()->getResponse()->setMeta('keywords', 'B2B платформа');
        wa()->getResponse()->setTitle('Личный кабинет');


        $this->view->assign([
            'sidebar_menu' => pb2bCabinetMenuProvider::sidebar($this->role),
            'header_menu' => pb2bCabinetMenuProvider::header(),
            'contact' => $this->contact,
            'company' => $this->company,
            'role' => $this->role,

            'ROLE_BUYER' => pb2bCompanyRole::BUYER->value,
            'ROLE_SUPPLIER' => pb2bCompanyRole::SUPPLIER->value,
        ]);
    }
}