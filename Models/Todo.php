<?php


class Todo
{
    public function __construct(
        public ?int $id = null, 
        public bool $isCompleted = false, 
        public string $text = '')
    {

    }

    public static function all(PDO $pdo)
    {
        $query = "SELECT * FROM todos";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $todos = [];
        foreach ($result as $todo) 
        {
            $todos[] = new static($todo['id'], $todo['completed'], $todo['text']);
        }
        return $todos;
    }

    public static function find(PDO $pdo, int $todoId)
    {
        $query = "SELECT * FROM todos WHERE id = :todoId";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':todoId' => $todoId
        ]);
        $todo = $stmt->fetch();

        if (!$todo) return null;
        
        return new static($todo['id'], $todo['completed'], $todo['text']);
    }

    public function create(PDO $pdo)
    {
        $query = "INSERT INTO todos (text) VALUES (:todo_title)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':todo_title' => $this->text
        ]);
        $this->id = $pdo->lastInsertId();
    }

    public function delete(PDO $pdo)
    {
        if ($this->id === null) {
            return null;
        }

        $query = "DELETE FROM todos WHERE id = :todoId";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':todoId' => $this->id
        ]);
    }

    public function update(PDO $pdo, array $properties)
    {
        $allowedProperties = ["completed", "text"];
        $query = "UPDATE todos SET ";
        $set = "";
        $execute = [];
        foreach ($properties as $key => $value) {
            if (!in_array($key, $allowedProperties)) {
                continue;
            }
            $set .= "$key = :$key,";
            $execute[":$key"] = $value;
        }
        if (strlen($set) === 0 || count($execute) === 0) {
            return null;
        }
        $set = substr($set, 0, -1);
        $query .= $set;
        $query .= " WHERE id = :id";

        $stmt = $pdo->prepare($query);
        $stmt->execute(array_merge($execute, [':id' => $this->id]));
        return true;
    }
}