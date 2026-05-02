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
                    method: 'POST',
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
                    method: 'POST',
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
    })
});