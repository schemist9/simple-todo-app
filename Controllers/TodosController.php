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

        header('Content-Type: application/json');

        if (!isset($data['id'])) {
            return json_encode([
                'status' => 'failure', 
                'message' => 'Todo id must be specified',
            ]);
        }

        $todoId = $data['id'];
        $todo = Todo::find($pdo, (int) $todoId);

        if (!$todo) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Todo not found'
            ]);
        }

        $todo->delete($pdo);

        return json_encode([
            'status' => 'success',
            'message' => 'Todo has been deleted'
        ]);
    }


    public function create()
    {
        $pdo = DB::getInstance();

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        header('Content-Type: application/json');

        if (!isset($data['text'])) {
            return json_encode([
                'status' => 'failure',
                'message' => 'Todo title must be provided'
            ]);
        }

        $todo = new Todo(text: $data['text']);
        $todo->create($pdo);

        return json_encode([
            'status' => 'success',
            'message' => 'Todo has been created',
            'data' => [
                'id' => $todo->id
            ]
        ]);
    }

    public function update()
    {
        $pdo = DB::getInstance();

        $rawInput = file_get_contents('php://input');
        $data = json_decode($rawInput, true);

        header('Content-Type: application/json');

        if (!isset($data['id'])) {
            return json_encode([
                'status' => 'failure', 
                'message' => 'Todo id must be specified',
            ]);
        }

        $todoId = $data['id'];

        $todo = Todo::find($pdo, $todoId);

        if (!$todo) {
            return json_encode([
                'status' => 'failure', 
                'message' => 'Todo not found',
            ]);
        }

        $todo->update($pdo, $data);

        return json_encode([
            'status' => 'success', 
            'message' => 'Todo updated',
        ]);
    }
}