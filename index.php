<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'router.php';
include 'Controllers/TodosController.php';
include './views/View.php';
include 'Controllers/IndexController.php';
include_once 'database.php';

DB::init();
$pdo = DB::getInstance();

$query = "CREATE TABLE IF NOT EXISTS todos (
    id INTEGER PRIMARY KEY,
    completed INTEGER NOT NULL DEFAULT FALSE,
    text TEXT NOT NULL)";
$stmt = $pdo->prepare($query);;
$stmt->execute();

function findTodo(PDO $pdo, int $todoId)
{
    $query = "SELECT * FROM todos WHERE id = :todoId";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':todoId' => $todoId
    ]);
    $todo = $stmt->fetch();
    return $todo;
}

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
    $todo = findTodo($pdo, $todoId);
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

$router = new Router();

$router->post('/todos/delete', [TodosController::class, 'delete']);
$router->get('/', [IndexController::class, 'index']);

if (isset($_POST['todo_title']))
{
    $todoTitle = $_POST['todo_title'];
    createTodo($pdo, $todoTitle);
    header("Location: /");
    exit;
}
else if (isset($_POST['action']) && $_POST['action'] === 'toggle')
{
    $todoId = $_POST['todo_id'];
    toggleTodo($pdo, (int) $todoId);
    header("Location: /");
    exit;
} else {
    $router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
}

