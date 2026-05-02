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

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        if (!isset($data['id'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'failure', 
                'message' => 'Todo id must be specified',
            ]);
            exit;
        }

        $todoId = $data['id'];
        
        $todo = Todo::find($pdo, (int) $todoId);
        if (!$todo) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'failure',
                'message' => 'Todo not found'
            ]);
            exit;
        }

        $todo->delete($pdo);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Todo has been deleted'
        ]);
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

    public function update()
    {
        $pdo = DB::getInstance();

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        if (!isset($data['id'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'failure', 
                'message' => 'Todo id must be specified',
            ]);
            exit;
        }

        $todoId = $data['id'];

        $todo = Todo::find($pdo, $todoId);

        if (!$todo) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'failure', 
                'message' => 'Todo not found',
            ]);
            exit;
        }

        $todo->update($pdo, $data);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success', 
            'message' => 'Todo updated',
        ]);
        exit;
    }
}