<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrations criando as tabelas de cache.
     */
    public function up(): void
    {
        // Tabela 'cache' para armazenar dados em cache com chave primária e tempo de expiração
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Chave única para identificar o cache
            $table->mediumText('value'); // Valor armazenado no cache
            $table->integer('expiration'); // Timestamp indicando quando o cache expira
        });

        // Tabela 'cache_locks' para controle de bloqueios (locks) no cache
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Chave do lock, vinculada ao cache
            $table->string('owner'); // Identificador do dono do lock
            $table->integer('expiration'); // Timestamp para expiração do lock
        });
    }

    /**
     * Reverte as migrations removendo as tabelas de cache e locks.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
