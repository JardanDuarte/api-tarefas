<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $query = $user->tasks()->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->with('comments')->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'status' => 'required|in:pendente,em_andamento,concluida'
        ]);

        $task = $this->taskService->createTask(
            Auth::user(),
            $validated
        );

        if (!empty($validated['comment'])) {
            $task->comments()->create([
                'content' => $validated['comment']
            ]);
        }

        return back()->with('success', 'Tarefa criada!');
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('update', $task);

        $this->taskService->updateTask($task, $request->only('title', 'description', 'status'));

        return back()->with('success', 'Atualizada!');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        $this->authorize('delete', $task);

        $task->delete();

        return back()->with('success', 'Deletada!');
    }

    public function create()
    {
        return view('tasks.form');
    }

    public function edit($id)
    {
        $task = auth()->user()
            ->tasks()
            ->with('comments')
            ->findOrFail($id);

        return view('tasks.form', compact('task'));
    }
}