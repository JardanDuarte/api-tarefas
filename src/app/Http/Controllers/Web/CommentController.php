<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(Request $request, $taskId)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ],
        [
            'content.required' => 'O campo conteúdo é obrigatório.',
            'content.string' => 'O campo conteúdo deve ser uma string.',
            'content.max' => 'O campo conteúdo deve ter no máximo :max caracteres.'
        ]);

        $task = Auth::user()->tasks()->findOrFail($taskId);

        $this->commentService->createComment(
            $task,
            $request->only('content')
        );

        return back()->with('success', 'Comentário criado!');
    }

    public function destroy($taskId, $commentId)
    {
        $comment = Comment::with('task')->findOrFail($commentId);

        // garante que o comentário pertence à task
        if ($comment->task_id != $taskId) {
            abort(404);
        }

        // garante que a task é do usuário
        abort_if($comment->task->user_id !== Auth::id(), 403);

        $this->commentService->deleteComment($comment);

        return back()->with('success', 'Comentário deletado!');
    }
}