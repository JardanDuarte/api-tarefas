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
        {{-- show error --}}
        @error('title')
        <div class="bg-red-600 text-white p-3 rounded mb-4">{{ $message }}</div>
        @enderror

    <textarea name="description" placeholder="Descrição"
        class="w-full bg-gray-800 border border-gray-700 p-2 rounded">{{ $task->description ?? '' }}</textarea>

    <select name="status" class="w-full bg-gray-800 border border-gray-700 p-2 rounded">
        <option value="pendente" {{ ($task->status ?? '')=='pendente'?'selected':'' }}>Pendente</option>
        <option value="em_andamento" {{ ($task->status ?? '')=='em_andamento'?'selected':'' }}>Em andamento</option>
        <option value="concluida" {{ ($task->status ?? '')=='concluida'?'selected':'' }}>Concluída</option>
    </select>

    <div>
    <label class="block text-sm text-gray-400 mb-2">Comentários</label>

    <div id="comments-container" class="space-y-2">
        <input name="comments[]"
            class="w-full bg-gray-800 border border-gray-700 p-2 rounded"
            placeholder="Digite um comentário">
    </div>

    <button type="button"
        onclick="addCommentField()"
        class="mt-2 bg-green-600 px-3 py-1 rounded text-sm">
        + adicionar comentário
    </button>
</div>

    <!-- botão principal -->
    <button class="bg-blue-600 px-4 py-2 rounded">
        {{ isset($task) ? 'Atualizar' : 'Criar' }}
    </button>
</form>

@endsection

<script>
function addCommentField() {
    const container = document.getElementById('comments-container');

    const input = document.createElement('input');
    input.name = 'comments[]';
    input.placeholder = 'Digite um comentário';
    input.className = 'w-full bg-gray-800 border border-gray-700 p-2 rounded';

    container.appendChild(input);
}
</script>
