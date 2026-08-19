<?php

abstract class pb2bWaproViewAction extends waViewAction
{
    /**
     * @var ?string
     */
    protected ?string $type;

    /**
     * @var ?string
     */
    protected ?string $js, $css;

    /**
     * pb2bWaproViewAction constructor.
     * @param null $params
     * @throws waException
     */
    public function __construct($params = null)
    {
        parent::__construct($params);
        $data = array(
            'app_path' => wa()->getAppPath('', pb2bWaproHelper::getAppId()),
            'app_backend_url' => wa()->getUrl(),
        );
        $this->view->assign($data);
    }

    /**
     * @param string $message
     * @throws waException
     */
    protected function setError(string $message = 'Нет прав доступа'): void
    {
        $this->view->assign(array(
            'error_message' => $message,
        ));
        $this->setTemplate(wa()->getAppPath('templates/actions/Error.html'));
    }

    /**
     * @param $clear_assign
     * @return string
     * @throws waException
     */
    public function display($clear_assign = true): string
    {
        $result = parent::display($clear_assign);
        $module = $this->type ?? waRequest::get('module', 'backend', waRequest::TYPE_STRING);
        $ext = array('js', 'css');
        $files = array();
        foreach ($ext as $e) {
            if (property_exists($this, $e) && !empty($this->$e)) {
                $path = $e.'/'.$this->$e.'.'.$e;
            } else {
                $path = $e.'/actions/'.$module.'/'.$module.ucfirst(waRequest::get('action', '', waRequest::TYPE_STRING)).'.'.$e;
            }
            if (file_exists(wa()->getAppPath($path))) {
                $files[$e] = $path;
            }
        }
        if (isset($files['js'])) {
            $result = '<script src="'.wa()->getAppStaticUrl().$files['js'].'?v='.wa()->getVersion().'"></script>'.$result;
        }
        if (isset($files['css'])) {
            $result = '<link href="'.wa()->getAppStaticUrl().$files['css'].'?v='.wa()->getVersion().'" rel="stylesheet" type="text/css">'.$result;
        }
        return $result;
    }
}