<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Task;

class CommentService
{
    public function createComment(Task $task, array $data): Comment
    {
        return $task->comments()->create($data);
    }

    public function deleteComment(Comment $comment): void
    {
        $comment->delete();
    }
}