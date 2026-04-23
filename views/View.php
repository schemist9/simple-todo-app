<?php

class View
{
    // create('php.path', 'layout.php')
    // 
    // 
    public static function create(string $view, ?string $layout = null, array $variables = [])
    {
        if ($layout) {
            ob_start();
            include __DIR__ . '/' . $layout;
            $data = ob_get_contents();
        }
        extract($variables);
        include __DIR__ . '/' . $view;
    }
}