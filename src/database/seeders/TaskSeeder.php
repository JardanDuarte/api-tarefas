<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\Comment;

use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
    public function run(): void
    {
        User::all()->each(function ($user) {
            Task::factory(5)
            ->create(['user_id' => $user->id])
            ->each(function ($task) {
                Comment::factory(rand(2, 5))->create([
                    'task_id' => $task->id
                ]);
                
            });
            
        });
    }
}
