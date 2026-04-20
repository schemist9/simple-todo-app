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

function toggleTodo(PDO $pdo, int $todoId)
{
    $query = "SELECT * FROM todos WHERE id = :todoId";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':todoId' => $todoId
    ]);
    $todo = $stmt->fetch();

    if (empty($todo)) {
        return false;
    }

    $nextState = $todo['completed'] === 0 ? 1 : 0;

    $query = "UPDATE todos
                SET completed = :nextState
                WHERE id = :todoId";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':nextState' => $nextState,
        ':todoId' => $todoId
    ]);
}

function deleteTodo(PDO $pdo, int $todoId)
{
    $query = "SELECT * FROM todos WHERE id = :todoId";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':todoId' => $todoId
    ]);
    $todo = $stmt->fetch();

    if (empty($todo)) {
        return false;
    }

    $query = "DELETE FROM todos WHERE id = :todoId";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':todoId' => $todoId
    ]);
}

if (isset($_POST['todo_title']))
{
    $todoTitle = $_POST['todo_title'];
    createTodo($pdo, $todoTitle);
}
else if (isset($_POST['action']) && $_POST['action'] === 'toggle')
{
    $todoId = $_POST['todo_id'];
    toggleTodo($pdo, (int) $todoId);
}
else if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $todoId = $_POST['todo_id'];
    deleteTodo($pdo, (int) $todoId);
}

$query = "SELECT * FROM todos";
$stmt = $pdo->prepare($query);
$stmt->execute();;
$result = $stmt->fetchAll();

function displayTodos(array $todos)
{
    echo '<ul class="todos">';
    foreach ($todos as $todo) {
        $todoCompleted = $todo['completed'] ? 'checked' : '';
        $todoTitle = htmlspecialchars($todo['text']);
        echo <<<TODO
    <li class="todo-item" data-todo-id="{$todo['id']}">
            <span>$todoTitle</span>
            <form action="/" method="POST">
                <input type="hidden" name="todo_id" value="{$todo['id']}">
                <input type="hidden" name="action" value="toggle">
                <input type="checkbox" name="todo_complete" id="todo_complete" $todoCompleted>
                <button type="submit">Update</button>
            </form>
            
            <form action="/" method="POST">
                <input type="hidden" name="todo_id" value="{$todo['id']}">
                <input type="hidden" name="action" value="delete">
                <button type="submit">Delete</button>
            </form>
    </li>
TODO;

    }
    echo '</ul>';
}

displayTodos($result);
