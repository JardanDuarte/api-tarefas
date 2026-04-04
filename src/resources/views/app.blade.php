<!DOCTYPE html>
<html>
<head>
    <title>Tasks</title>
</head>
<body>

<h1>Minhas Tarefas</h1>

<button onclick="loadTasks()">Carregar Tasks</button>

<ul id="tasks"></ul>

<script>
const token = "SEU_TOKEN_AQUI";

function loadTasks() {
    fetch('/api/v1/tasks', {
        headers: {
            'Authorization': 'Bearer ' + token
        }
    })
    .then(res => res.json())
    .then(data => {
        const list = document.getElementById('tasks');
        list.innerHTML = '';

        data.data.forEach(task => {
            const li = document.createElement('li');
            li.innerText = task.title + ' (' + task.status + ')';
            list.appendChild(li);
        });
    });
}
</script>

</body>
</html>