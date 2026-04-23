<?php

include __DIR__ . '/../database.php';
include_once __DIR__ . '/../Models/Todo.php';
class TodosController 
{
    public function delete()
    {
            $pdo = DB::getInstance();
            $todoId = $_POST['todo_id'];
            
            Todo::find($pdo, (int) $todoId)->delete($pdo);

            header('Location: /');
            // exit;
    }
}