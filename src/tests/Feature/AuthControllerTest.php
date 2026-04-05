<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_pode_se_registrar()
    {
        $data = [
            'name' => 'Usuário 1',
            'email' => 'user1@teste.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
                ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email'],'token']]);

        $this->assertDatabaseHas('users', ['email' => 'user1@teste.com']);
    }

    public function test_registro_falha_com_dados_invalidos()
    {
        $response = $this->postJson('/api/v1/register', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_nao_permite_email_duplicado()
    {
        User::factory()->create([
            'email' => 'teste@test.com'
        ]);

        $data = [
            'name' => 'Outro',
            'email' => 'teste@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        $response = $this->postJson('/api/v1/register', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_usuario_pode_fazer_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('12345678')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => '12345678'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token'
                ]
            ]);
    }

    public function test_login_falha_com_credenciais_invalidas()
    {
        $user = User::factory()->create([
            'password' => Hash::make('12345678')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'senha_errada'
        ]);

        $response->assertStatus(401)->assertJson(['success' => false]);
    }

    public function test_login_falha_com_usuario_inexistente()
    {
        $response = $this->postJson('/api/v1/login', [
            'email' => 'naoexiste@test.com',
            'password' => '12345678'
        ]);

        $response->assertStatus(401);
    }

    public function test_usuario_pode_fazer_logout()
    {
        $user = User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('/api/v1/logout');

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
}