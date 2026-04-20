<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'index.html';

$pdo = new PDO('sqlite:mydb.sq3', '', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$query = "CREATE TABLE IF NOT EXISTS todos (
    id INTEGER PRIMARY KEY,
    completed INTEGER NOT NULL DEFAULT FALSE,
    text TEXT NOT NULL)";
$stmt = $pdo->prepare($query);;
$stmt->execute();

function createTodo(PDO $pdo, string $todoTitle)
{
    $query = "INSERT INTO todos (text) VALUES (:todo_title)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':todo_title' => $todoTitle
    ]);
}

if (isset($_POST['todo_title']))
{
    $todoTitle = $_POST['todo_title'];
    createTodo($pdo, $todoTitle);
}

$query = "SELECT * FROM todos";
$stmt = $pdo->prepare($query);
$stmt->execute();;
$result = $stmt->fetchAll();

function displayTodos(array $todos)
{
    echo '<ul>';
    foreach ($todos as $todo) {
        $todoCompleted = $todo['completed'] ? 'checked' : '';
        echo <<<TODO
    <li data-todo-id="{$todo['id']}">
        <span>{$todo['text']}</span>
        <input type="checkbox" name="todo_complete" id="todo_complete" $todoCompleted>
    </li>
TODO;

    }
    echo '</ul>';
}

displayTodos($result);