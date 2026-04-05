@extends('layouts.app')
@section('content')
        <button class="bg-gray-500 text-white px-4 rounded">Filtrar</button>
    </form>

    <!-- Criar tarefa -->
    <form method="POST" action="/tasks" class="space-y-2 mb-6">
        @csrf
        <input name="title" placeholder="Título" class="w-full border p-2 rounded">
        <textarea name="description" placeholder="Descrição" class="w-full border p-2 rounded"></textarea>

        <select name="status" class="border p-2 rounded w-full">
            <option value="pendente">Pendente</option>
            <option value="em_andamento">Em andamento</option>
            <option value="concluida">Concluída</option>
        </select>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Criar</button>
    </form>

    <!-- Lista -->
    <div class="space-y-4">
        @foreach($tasks as $task)
        <div class="border p-4 rounded">

            <!-- Update -->
            <form method="POST" action="/tasks/{{ $task->id }}" class="space-y-2">
                @csrf
                @method('PUT')

                <input name="title" value="{{ $task->title }}" class="w-full border p-2 rounded">

                <textarea name="description" class="w-full border p-2 rounded">{{ $task->description }}</textarea>

                <select name="status" class="border p-2 rounded w-full">
                    <option value="pendente" {{ $task->status=='pendente'?'selected':'' }}>Pendente</option>
                    <option value="em_andamento" {{ $task->status=='em_andamento'?'selected':'' }}>Em andamento</option>
                    <option value="concluida" {{ $task->status=='concluida'?'selected':'' }}>Concluída</option>
                </select>

                <button class="bg-yellow-500 text-white px-3 py-1 rounded">Atualizar</button>
            </form>

            <!-- Delete -->
            <form method="POST" action="/tasks/{{ $task->id }}" class="mt-2">
                @csrf
                @method('DELETE')
                <button class="bg-red-500 text-white px-3 py-1 rounded">Deletar</button>
            </form>

            <!-- Comentários -->
            <div class="mt-4">
                <h4 class="font-semibold">Comentários</h4>

                <form method="POST" action="/tasks/{{ $task->id }}/comments" class="flex gap-2 mt-2">
                    @csrf
                    <input name="content" placeholder="Novo comentário" class="flex-1 border p-2 rounded">
                    <button class="bg-green-500 text-white px-2 rounded">+</button>
                </form>

                @foreach($task->comments as $comment)
                <div class="flex justify-between mt-2 text-sm">
                    <span>{{ $comment->content }}</span>

                    <form method="POST" action="/tasks/{{ $task->id }}/comments/{{ $comment->id }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500">x</button>
                    </form>
                </div>
                @endforeach
            </div>

        </div>
        @endforeach
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>

</div>
@endsection