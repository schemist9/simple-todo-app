<?php

include __DIR__ . '/../database.php';
include_once __DIR__ . '/../Models/Todo.php';
class TodosController 
{
    public function index()
    {
        $pdo = DB::getInstance();
        $todos = Todo::all($pdo);
        View::create('index.php', 'layout.php', ['todos' => $todos]);
    }

    public function delete()
    {
            $pdo = DB::getInstance();
            $todoId = $_POST['todo_id'];
            
            Todo::find($pdo, (int) $todoId)->delete($pdo);

            header('Location: /');
            // exit;
    }
}