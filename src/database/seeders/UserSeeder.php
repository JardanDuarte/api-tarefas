<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Usuário 1',
            'email' => 'user1@teste.com',
            'password' => '12345678',
        ]);

        User::create([
            'name' => 'Usuário 2',
            'email' => 'user2@teste.com',
            'password' => '12345678',
        ]);

        User::create([
            'name' => 'Usuário 3',
            'email' => 'user3@teste.com',
            'password' => '12345678',
        ]);
    }
}