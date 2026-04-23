<?php

include_once __DIR__ . '/../database.php';

class IndexController
{
    public function index()
    {
        $pdo = DB::getInstance();
        $todos = Todo::all($pdo);
        View::create('index.php', 'layout.php', ['todos' => $todos]);
    }
}