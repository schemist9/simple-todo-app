<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'router.php';
include 'Controllers/TodosController.php';
include './View.php';
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

$router = new Router();

$router->delete('/todos/delete', [TodosController::class, 'delete']);
$router->post('/todos', [TodosController::class, 'create']);
$router->patch('/todos/update', [TodosController::class, 'update']);
$router->get('/', [TodosController::class, 'index']);


echo $router->resolve($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


