<?php

namespace App\Http\Controllers\Api;

use App\Services\TaskService;
Use App\Http\Requests\FilterTaskRequest;
Use App\Http\Requests\StoreTaskRequest;
Use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

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

        if (!empty($validated['created_at'])) {
            $query->whereDate('created_at', $validated['created_at']);
        }

        $perPage = $validated['per_page'] ?? 10;

        return TaskResource::collection(
            $query->paginate($perPage)
        );
    }

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

    public function show($taskId)
    {
        $task = Task::with('comments')->findOrFail($taskId);

        $this->authorize('view', $task);

        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, $taskId)
    {
        $task = Task::findOrFail($taskId);

        $this->authorize('update', $task);

        $task = $this->taskService->updateTask(
            $task,
            $request->validated()
        );

        $task->load('comments');

        return new TaskResource($task);
    }

    public function destroy($taskId)
    {
        $task = Task::findOrFail($taskId);

        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarefa deletada com sucesso'
        ], 200);
    }
}