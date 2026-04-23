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

        if (!$todo) return new static();
        
        return new static($todo['id'], $todo['completed'], $todo['text']);
    }

    public function create(PDO $pdo)
    {
        $query = "INSERT INTO todos (text) VALUES (:todo_title)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':todo_title' => $this->text
        ]);
    }

    public function toggle(PDO $pdo, int $todoId)
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
}