<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;

class TaskService
{
    public function createTask(User $user, array $data): Task
    {
        return $user->tasks()->create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}