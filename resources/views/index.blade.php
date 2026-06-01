<!DOCTYPE html>
<html>
<head>
    <title>Todo List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="text-center mb-3">
    <small class="text-muted">
        Total: {{ $total }} |
        Active: {{ $active }} |
        Done: {{ $done }}
    </small>
</div>
<div class="d-flex justify-content-center gap-2 mb-3">

    <a href="/?filter=all"
       class="btn btn-sm {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
        All
    </a>

    <a href="/?filter=active"
       class="btn btn-sm {{ $filter == 'active' ? 'btn-primary' : 'btn-outline-primary' }}">
        Active
    </a>

    <a href="/?filter=done"
       class="btn btn-sm {{ $filter == 'done' ? 'btn-primary' : 'btn-outline-primary' }}">
        Done
    </a>

</div>

<div class="container py-5" style="max-width: 600px;">

    <h2 class="text-center mb-4">📝 My Todo List</h2>

    <!-- ADD TASK -->
    <div class="d-flex gap-2 mb-4">
        <input type="text" id="taskInput" class="form-control" placeholder="Enter new task...">
        <button class="btn btn-primary" onclick="addTask()">Add</button>
    </div>

    <!-- TASK LIST -->
    <div class="card shadow-sm">
        <ul class="list-group list-group-flush" id="taskList">
            @forelse($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <form method="POST" action="/tasks/{{ $task->id }}" class="m-0">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm {{ $task->is_done ? 'btn-success' : 'btn-outline-secondary' }}">
                            {{ $task->is_done ? 'Done ✔' : 'Mark done' }}
                        </button>
                    </form>

                    <span class="{{ $task->is_done ? 'text-decoration-line-through text-muted' : '' }}">
                        {{ $task->title }}
                    </span>

                    <form method="POST" action="/tasks/{{ $task->id }}" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>

                </li>
            @empty
                <li class="list-group-item text-center text-muted">
                    No tasks yet
                </li>
            @endforelse

        </ul>
    </div>

</div>
<script>
    function addTask() {
        let input = document.getElementById('taskInput');
        let title = input.value;

        if (!title.trim()) return;

        fetch('/tasks/ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ title: title })
        })
            .then(res => res.json())
            .then(task => {

                let list = document.getElementById('taskList');

                let li = document.createElement('li');
                li.className = "list-group-item d-flex justify-content-between align-items-center";

                li.innerHTML = `
            <span>${task.title}</span>
            <small class="text-muted">new</small>
        `;

                list.prepend(li);

                input.value = "";
            });
    }
</script>
</body>
</html>
