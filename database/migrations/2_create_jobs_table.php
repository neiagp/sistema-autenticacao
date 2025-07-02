<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrations para criar as tabelas de jobs.
     */
    public function up(): void
    {
        // Tabela 'jobs': armazena os trabalhos pendentes na fila
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // ID incremental
            $table->string('queue')->index(); // Nome da fila onde o job está
            $table->longText('payload'); // Dados serializados do job
            $table->unsignedTinyInteger('attempts'); // Número de tentativas feitas
            $table->unsignedInteger('reserved_at')->nullable(); // Timestamp quando reservado para execução
            $table->unsignedInteger('available_at'); // Timestamp quando ficou disponível para execução
            $table->unsignedInteger('created_at'); // Timestamp da criação do job
        });

        // Tabela 'job_batches': agrupa vários jobs em lotes para controle conjunto
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary(); // ID do lote, string (UUID)
            $table->string('name'); // Nome do lote
            $table->integer('total_jobs'); // Total de jobs no lote
            $table->integer('pending_jobs'); // Jobs pendentes
            $table->integer('failed_jobs'); // Jobs que falharam
            $table->longText('failed_job_ids'); // IDs dos jobs que falharam (serializados)
            $table->mediumText('options')->nullable(); // Opções adicionais do lote (serializadas)
            $table->integer('cancelled_at')->nullable(); // Timestamp de cancelamento, se houver
            $table->integer('created_at'); // Timestamp de criação
            $table->integer('finished_at')->nullable(); // Timestamp quando lote finalizou
        });

        // Tabela 'failed_jobs': registra jobs que falharam para análise e reprocessamento
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // ID incremental
            $table->string('uuid')->unique(); // UUID único para identificar o job
            $table->text('connection'); // Nome da conexão de fila usada
            $table->text('queue'); // Nome da fila
            $table->longText('payload'); // Dados do job
            $table->longText('exception'); // Informação da exceção/erro ocorrido
            $table->timestamp('failed_at')->useCurrent(); // Data e hora da falha (padrão para agora)
        });
    }

    /**
     * Reverte as migrations removendo as tabelas criadas.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('failed_jobs');
    }
};
