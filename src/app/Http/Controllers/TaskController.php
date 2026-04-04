<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use App\Services\TaskService;
Use App\Http\Requests\FilterTaskRequest;
Use App\Http\Requests\StoreTaskRequest;
Use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Exception;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    // Listar as tarefas de acordo com os filtros passados na requisição
    public function index(FilterTaskRequest $request)
    {
        $validated = $request->validated();

        $query = $request->user()
        ->tasks()
        ->with(['comments' => function ($q) {
            $q->latest();
        }])
        ->orderBy('created_at', 'desc');

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['date'])) {
            $query->whereDate('created_at', $validated['date']);
        }

        $perPage = $validated['per_page'] ?? 10;

        return TaskResource::collection(
            $query->paginate($perPage)
        );
    }

    // Criar Tarefas
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask(
            $request->user(),
            $request->validated()
        );

        // Estou carregando os comentarios somente para manter o padrão de retorno da api.
        $task->load('comments');

        return (new TaskResource($task))->response()->setStatusCode(201);
    }

    // Mostrar tarefa específica
    public function show($taskId)
    {
        $task = Task::with('comments')->findOrFail($taskId);

        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    // Atualizar uma tarefa
    public function update(UpdateTaskRequest $request, $taskId)
    {
        try {
            $task = Task::findOrFail($taskId);

            $this->authorize('update', $task);

            $task = $this->taskService->updateTask(
                $task,
                $request->validated()
            );

            $task->load('comments');

            return new TaskResource($task);
        } catch (Exception $e) {
            Log::error('Erro ao atualizar tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar tarefa'
            ], 500);
        }
    }

    // Deletar uma tarefa
    public function destroy($taskId)
    {
        try {
            $task = Task::findOrFail($taskId);

            $this->authorize('delete', $task);

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tarefa deletada com sucesso'
            ], 200);
        } catch (Exception $e) {
            Log::error('Erro ao deletar tarefa', [
                'task_id' => $taskId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar tarefa'
            ], 500);
        }
    }
}
