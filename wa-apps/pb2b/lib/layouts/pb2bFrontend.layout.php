<?php
class pb2bFrontendLayout extends waLayout{
    private string|null $head_path = null;
    private string|null $footer_path = null;

    public function __construct($head_path = '', $footer_path = '')
    {
        parent::__construct();
        $this->head_path = $head_path;
        $this->footer_path = $footer_path;
    }

    public function execute()
    {
        $this->setThemeTemplate('index.html');
        wa()->getResponse()->setMeta('keywords', 'B2B платформа');
        $this->view->assign('head_path', $this->head_path);
        $this->view->assign('footer_path', $this->footer_path);
    }
}