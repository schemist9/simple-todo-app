<?php
echo '<ul class="todos">';
    foreach ($todos as $key => $todo) {
        $todoCompleted = $todo->isCompleted ? 'checked' : '';
        $todoTitle = htmlspecialchars($todo->text);
        echo <<<TODO
    <li class="todo-item" data-todo-id="{$todo->id}">
            <span>$todoTitle</span>
            <input class="todo-toggle" type="checkbox" name="todo_complete" id="todo_complete" $todoCompleted autocomplete="off">
            <button class="todo-text-change">Change text</button>
            <button class="todo-remove">Delete</button>
    </li>
TODO;
    }
echo '</ul>';
