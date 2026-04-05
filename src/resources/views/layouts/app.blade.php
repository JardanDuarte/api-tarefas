<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans">

<div class="max-w-5xl mx-auto p-6">

    <nav class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gerenciador de Tarefas</h1>

        @auth
        <form method="POST" action="/logout">
            @csrf
            <button class="bg-red-600 px-4 py-2 rounded">Logout</button>
        </form>
        @endauth
    </nav>

    @yield('content')

</div>

</body>
</html>