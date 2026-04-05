<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $task;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);
    }

    public function test_usuario_pode_listar_comentarios_da_tarefa()
    {
        Comment::factory()->count(3)->create([
            'task_id' => $this->task->id
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/tasks/{$this->task->id}/comments");
        
        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_usuario_nao_pode_listar_comentarios_de_tarefa_que_nao_e_dele()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/v1/tasks/{$task->id}/comments");

        $response->assertStatus(404);
    }

    public function test_paginacao_de_comentarios()
    {
        Comment::factory()->count(15)->create([
            'task_id' => $this->task->id
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/tasks/{$this->task->id}/comments?per_page=5");

        $response->assertStatus(200)->assertJsonCount(5, 'data');
    }

    public function test_per_page_invalido_retorna_erro()
    {
        $response = $this->actingAs($this->user)->getJson("/api/v1/tasks/{$this->task->id}/comments?per_page=100");

        $response->assertStatus(422);
    }

    public function test_usuario_pode_criar_comentario()
    {
        $data = [
            'content' => 'Comentário teste'
        ];

        $response = $this->actingAs($this->user)->postJson("/api/v1/tasks/{$this->task->id}/comments", $data);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'content' => 'Comentário teste'
                ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'Comentário teste',
            'task_id' => $this->task->id
        ]);
    }

    public function test_criar_comentario_falha_com_dados_invalidos()
    {
        $response = $this->actingAs($this->user)->postJson("/api/v1/tasks/{$this->task->id}/comments", []);

        $response->assertStatus(422)->assertJsonValidationErrors(['content']);
    }

    public function test_usuario_pode_deletar_comentario()
    {
        $comment = Comment::factory()->create([
            'task_id' => $this->task->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/tasks/{$this->task->id}/comments/{$comment->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id
        ]);
    }

    public function test_nao_deleta_comentario_de_outra_task()
    {
        $otherTask = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $comment = Comment::factory()->create([
            'task_id' => $otherTask->id
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/v1/tasks/{$this->task->id}/comments/{$comment->id}");

        $response->assertStatus(404);
    }

    public function test_usuario_nao_pode_deletar_comentario_de_outro_usuario()
    {
        $task = Task::factory()->create();

        $comment = Comment::factory()->create([
            'task_id' => $task->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/tasks/{$task->id}/comments/{$comment->id}");

        $response->assertStatus(403);
    }
}