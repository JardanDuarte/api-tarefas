<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Services\CommentService;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Controllers\Controller;


class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    // Listar comentários de uma tarefa
    public function index(Request $request, $taskId)
    {
        $validated = $request->validate([
            'per_page' => 'sometimes|integer|min:1|max:50'
        ]);

        $task = $request->user()->tasks()->findOrFail($taskId);

        $perPage = $validated['per_page'] ?? 10;

        $comments = $task->comments()->latest()->paginate($perPage);

        return CommentResource::collection($comments);
    }

    // Criar comentário
    public function store(StoreCommentRequest $request, $taskId)
    {
        $task = $request->user()->tasks()->findOrFail($taskId);

        $comment = $this->commentService->createComment(
            $task,
            $request->validated()
        );

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    // Deletar comentário
    public function destroy($taskId, $commentId)
    {
        $comment = Comment::with('task')->findOrFail($commentId);

        if ($comment->task_id != $taskId) {
            abort(404);
        }

        $this->authorize('delete', $comment);

        $this->commentService->deleteComment($comment);

        return response()->noContent();
    }
}