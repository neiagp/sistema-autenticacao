<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Executa os seeders para popular a tabela users com dados de exemplo.
     *
     * Este método:
     * - Limpa a tabela users usando truncate para evitar duplicatas.
     * - Cria três usuários de exemplo com nomes, emails e senhas hash.
     */
    public function run(): void
    {
        // Remove todos os registros da tabela users para começar limpo
        User::truncate();

        // Cria os usuários de exemplo com senha hash segura
        User::create([
            'name' => 'Usuário Exemplo 0',
            'email' => 'user_zero@sistemaautenticacao.com.br',
            'password' => Hash::make('senha@12345678'),
        ]);

        User::create([
            'name' => 'Usuário Exemplo 1',
            'email' => 'user_um@sistemaautenticacao.com.br',
            'password' => Hash::make('senha@123'),
        ]);

        User::create([
            'name' => 'Usuário Exemplo 2',
            'email' => 'user_dois@sistemaautenticacao.com.br',
            'password' => Hash::make('senha@456'),
        ]);
    }
}
