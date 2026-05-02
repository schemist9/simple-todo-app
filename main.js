document.addEventListener('DOMContentLoaded', event => {
    const todoContainer = document.querySelector('.todos');

    todoContainer.addEventListener('click', event => {
        const todo = event.target.closest('[data-todo-id]');
        if (todo) {
            const todoId = todo.dataset.todoId;

            if (event.target.classList.contains('todo-toggle')) {
                const checkbox = todo.querySelector('[type=checkbox]');
                
                console.log(`Clicked on todo with the id ${todoId}`);
                fetch(`/todos/update`, {
                    method: 'PATCH',
                    headers: {
                        "Content-Type": 'application/json'
                    },
                    body: JSON.stringify({
                        id: todoId,
                        'completed': checkbox.checked
                    })
                })
                .then(data => console.log(data));
            } else if (event.target.classList.contains('todo-remove')) {
                fetch('/todos/delete', {
                    method: 'DELETE',
                    headers: {
                        "Content-Type": 'application/json'
                    },
                    body: JSON.stringify({
                        id: todoId
                    })
                })
                .then(response => response.json())
                .then(response => {
                    if (response.status === 'success') {
                        todo.remove();
                    }
                });
            }
        }
    });

    const form = document.querySelector('.todo-create');
    form.addEventListener('submit', event => {
        event.preventDefault();
        const formFieldsData = new FormData(form);
        const text = formFieldsData.get('text');
        console.log(formFieldsData);

        fetch('/todos', {
            method: 'POST',
            headers: {
                "Content-Type": 'application/json'
            },
            body: JSON.stringify({
                text
            })
        })
        .then(response => response.json())
        .then(response => {
            if (response.status === 'success') {
                createTodoElement(text, response.data.id);
            }
        });
    })

    const createTodoElement = (text, id) => {
        const todo = document.createElement('li');
        todo.classList.add('todo-item');
        todo.setAttribute('data-todo-id', id);

        const todoText = document.createElement('span');
        todoText.textContent = text;

        const todoCheckbox = document.createElement('input');
        todoCheckbox.setAttribute('id', 'todo_complete');
        todoCheckbox.classList.add('todo-toggle');
        todoCheckbox.setAttribute('type', 'checkbox');
        todoCheckbox.setAttribute('name', 'todo_complete');
        todoCheckbox.setAttribute('autocomplete', 'off');

        const todoDeleteButton = document.createElement('button');
        todoDeleteButton.classList.add('todo-remove');
        todoDeleteButton.textContent = 'Delete';

        todo.append(todoText, todoCheckbox, todoDeleteButton);

        todoContainer.append(todo);
    };
});