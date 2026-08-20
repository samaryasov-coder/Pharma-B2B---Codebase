<?php
class pb2bFrontendHomeAction extends pb2bFrontendAction
{
    public function executeAction()
    {
        $this->setLayout(new pb2bFrontendLayout('header.html', 'footer.html'));
        wa()->getResponse()->setTitle('Pharma B2B');
        $this->setThemeTemplate('home.html');
    }
}