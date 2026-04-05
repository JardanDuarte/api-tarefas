@extends('layouts.app')

@section('content')

<h2 class="text-xl font-bold mb-4">
    {{ isset($task) ? 'Editar tarefa' : 'Nova tarefa' }}
</h2>

<form method="POST" action="{{ isset($task) ? '/tasks/'.$task->id : '/tasks' }}" class="space-y-4">
    @csrf
    @if(isset($task))
        @method('PUT')
    @endif

    <input name="title" placeholder="Título"
        value="{{ $task->title ?? '' }}"
        class="w-full bg-gray-800 border border-gray-700 p-2 rounded">

    <textarea name="description" placeholder="Descrição"
        class="w-full bg-gray-800 border border-gray-700 p-2 rounded">{{ $task->description ?? '' }}</textarea>

    <select name="status" class="w-full bg-gray-800 border border-gray-700 p-2 rounded">
        <option value="pendente" {{ ($task->status ?? '')=='pendente'?'selected':'' }}>Pendente</option>
        <option value="em_andamento" {{ ($task->status ?? '')=='em_andamento'?'selected':'' }}>Em andamento</option>
        <option value="concluida" {{ ($task->status ?? '')=='concluida'?'selected':'' }}>Concluída</option>
    </select>

    <button class="bg-blue-600 px-4 py-2 rounded">
        {{ isset($task) ? 'Atualizar' : 'Criar' }}
    </button>
</form>


@if(isset($task))
<div class="mt-8">
    <h3 class="font-bold mb-2">Comentários</h3>

    <form method="POST" action="/tasks/{{ $task->id }}/comments" class="flex gap-2 mb-4">
        @csrf
        <input name="content" class="flex-1 bg-gray-800 border border-gray-700 p-2 rounded" placeholder="Novo comentário">
        <button class="bg-green-600 px-3 rounded">+</button>
    </form>

    <div class="space-y-2">
        @foreach($task->comments as $comment)
        <div class="flex justify-between bg-gray-800 p-2 rounded">
            <span>{{ $comment->content }}</span>

            <form method="POST" action="/tasks/{{ $task->id }}/comments/{{ $comment->id }}">
                @csrf
                @method('DELETE')
                <button class="text-red-400">x</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
