<?php

class pb2bFrontendNoSlashAction extends waViewAction
{
    public function execute()
    {
        wa()->getResponse()->setTitle('Перенаправление..');
        $this->setThemeTemplate('blank.html');

        $uri_data = explode('?', waRequest::server('REQUEST_URI'));
        $uri = $uri_data[0].'/';
        if (isset($uri_data[1]))
            $uri .= '?'.$uri_data[1];
        wa()->getResponse()->redirect($uri, 301);
    }
}
