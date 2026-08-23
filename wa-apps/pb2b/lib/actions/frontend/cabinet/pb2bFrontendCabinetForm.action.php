<?php
abstract class pb2bFrontendCabinetFormAction extends pb2bFrontendCabinetAction
{
    protected string $module = '';

    protected function handle($path_template)
    {
        $section = waRequest::param('section', '', waRequest::TYPE_STRING_TRIM);

        $name = implode('', array_map(
            fn($i, $v) => $i === 0 ? $v : ucfirst($v),
            array_keys($parts = explode('/', $section)),
            $parts));

        $method = $name.'Action';
        $template_name = "$path_template/$name.html";

        if (!method_exists($this, $method)) {
            $this->setThemeTemplate('error.form.html');
            return;
        }

        $this->setCabinetThemeTemplate($template_name, false);
        $this->$method();
    }
}