<?php

class View
{
    public static function create(string $view, ?string $layout = null, array $variables = [])
    {
        extract($variables);
        ob_start();
        include __DIR__ . "/views/" . $view;
        $data = ob_get_clean();

        if ($layout) {
            include __DIR__ . "/views/" . $layout;
        } else {
            echo $data;
        }
    }
}