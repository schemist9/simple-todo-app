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

        if (!isset($todoId)) {
            header('Location: /');
            exit;
        }
        
        $todo = Todo::find($pdo, (int) $todoId);
        if (!$todo) {
            header("Location: /");
            exit;
        }

        $todo->delete($pdo);

        header('Location: /');
        exit;
    }


    public function create()
    {
        $pdo = DB::getInstance();

        if (isset($_POST['todo_title']))
        {
            $todoTitle = $_POST['todo_title'];
            $todo = new Todo(null, false, $todoTitle);
            $todo->create($pdo);
            header("Location: /");
            exit;
        }
    }
}