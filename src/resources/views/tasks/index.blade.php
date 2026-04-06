@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold">Minhas tarefas</h2>

    <a href="/tasks/create" class="bg-blue-600 px-4 py-2 rounded">+ Nova tarefa</a>
</div>

<form method="GET" class="mb-4">
    <select name="status" class="bg-gray-800 border border-gray-700 p-2 rounded">
        <option value="">Todos</option>
        <option value="pendente">Pendente</option>
        <option value="em_andamento">Em andamento</option>
        <option value="concluida">Concluída</option>
    </select>
    <button class="bg-gray-700 px-3 py-2 rounded ml-2">Filtrar</button>
</form>

@if(session('success'))
    <div class="bg-green-600 text-white p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-600 text-white p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="space-y-4">
    @foreach($tasks as $task)
    <div class="bg-gray-800 p-4 rounded">

        <div class="flex justify-between items-start">
            <div>
                <p class="font-semibold">Título: {{ $task->title }}</p>
                <p class="text-sm text-gray-400">Status: {{ $task->status }}</p>

                @if($task->description)
                    <p class="text-sm mt-2 text-gray-300">Descrição: {{ $task->description }}</p>
                @endif
            </div>

            <div class="flex gap-2">
                <a href="/tasks/{{ $task->id }}/edit" class="bg-yellow-600 px-3 py-1 rounded">Editar</a>

                <form method="POST" action="/tasks/{{ $task->id }}"
                onsubmit="return confirm('Tem certeza que deseja deletar esta tarefa?')">
                @csrf
                @method('DELETE')
                    <button class="bg-red-600 px-3 py-1 rounded">X</button>
                </form>
            </div>
        </div>

        @if($task->comments->count())
        <div class="mt-3 border-t border-gray-700 pt-3 space-y-2">
            Comentários:
            <br/>
            @foreach($task->comments as $comment)
                <div class="text-sm text-gray-300 bg-gray-700 p-2 rounded">
                    {{ $comment->content }}
                </div>
            @endforeach
        </div>
        @endif

    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $tasks->links() }}
</div>

@endsection