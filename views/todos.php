<?php
echo '<ul class="todos">';
    foreach ($todos as $key => $todo) {
        $todoCompleted = $todo->isCompleted ? 'checked' : '';
        $todoTitle = htmlspecialchars($todo->text);
        echo <<<TODO
    <li class="todo-item" data-todo-id="{$todo->id}">
            <span>$todoTitle</span>
            <input type="checkbox" name="todo_complete" id="todo_complete" value="1" $todoCompleted>
            
            <form action="/todos/delete" method="POST">
                <input type="hidden" name="todo_id" value="{$todo->id}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit">Delete</button>
            </form>
    </li>
TODO;
    }
echo '</ul>';
