<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Executa os seeders da aplicação.
     * 
     * Aqui, chama o UserSeeder para popular a tabela users com dados iniciais.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
    }
}
