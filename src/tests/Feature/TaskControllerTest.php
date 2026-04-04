<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Services\TaskService;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_usuario_pode_listar_suas_tarefas()
    {
        Task::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/tasks');

        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_filtrar_tarefas_por_status()
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pendente'
        ]);

        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'concluida'
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/v1/tasks?status=pendente');

        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }

    public function test_usuario_pode_criar_tarefa()
    {
        $data = [
            'title' => 'Nova tarefa',
            'description' => 'Descrição teste',
            'status' => 'pendente'
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/tasks', $data);

        $response->assertStatus(201)->assertJsonFragment(['title' => 'Nova tarefa']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Nova tarefa',
            'user_id' => $this->user->id
        ]);
    }

    public function test_usuario_pode_ver_uma_tarefa()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)->assertJsonFragment(['id' => $task->id]);
    }

    public function test_usuario_nao_pode_ver_tarefa_de_outro_usuario()
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    public function test_usuario_pode_atualizar_uma_tarefa()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'title' => 'Tarefa atualizada',
            'status' => 'em_andamento'
        ];

        $response = $this->actingAs($this->user)->putJson("/api/v1/tasks/{$task->id}", $data);

        $response->assertStatus(200)->assertJsonFragment(['title' => 'Tarefa atualizada']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Tarefa atualizada',
            'status' => 'em_andamento'
        ]);
    }

    public function test_usuario_pode_deletar_tarefa()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }

    public function test_erro_ao_atualizar_tarefa_retorna_500()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $mock = Mockery::mock(TaskService::class);
        $mock->shouldReceive('updateTask')->andThrow(new \Exception('Erro'));

        $this->app->instance(TaskService::class, $mock);

        $response = $this->actingAs($this->user)->putJson("/api/v1/tasks/{$task->id}", ['title' => 'Teste']);

        $response->assertStatus(500)->assertJson(['success' => false]);
    }
}