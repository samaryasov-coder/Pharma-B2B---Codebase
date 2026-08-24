<?php
class pb2bFrontendCabinetAction extends pb2bFrontendAction
{
    use pb2bFrontendCabinetTrait;

    protected function preExecute()
    {
        parent::preExecute();
        $this->view->assign('contact', $this->context->contact());
    }

    protected function setCabinetThemeTemplate(string $template, bool $check_role = true): void
    {
        $path = 'html/cabinet/';
        $role = $this->context->role();

        if ($check_role && !is_null($role)) {
            $path .= $role->value . '/';
        }

        $this->setThemeTemplate($path . $template);
    }

    public function __construct()
    {
        $this->initContext();
        $this->layout = new pb2bFrontendCabinetLayout($this->context->contact(), $this->context->company(), $this->context->role()?->value);

        parent::__construct();
    }
}