<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Executa as migrations, criando as tabelas necessárias para o sistema de autenticação.
     */
    public function up(): void
    {
        // Cria a tabela 'users' para armazenar os dados dos usuários
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Chave primária auto-increment
            $table->string('name'); // Nome do usuário
            $table->string('email')->unique(); // E-mail único para login e comunicação
            $table->timestamp('email_verified_at')->nullable(); // Data de verificação do e-mail (pode ser nulo)
            $table->string('password'); // Senha do usuário (armazenada de forma segura)
            $table->string('profile_photo')->nullable(); // Caminho para foto do perfil (opcional)
            $table->rememberToken(); // Token para lembrar login (sessão persistente)
            $table->timestamps(); // Campos created_at e updated_at automáticos
        });

        // Cria a tabela 'password_reset_tokens' para gerenciar tokens de redefinição de senha
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // E-mail do usuário, chave primária
            $table->string('token'); // Token para redefinir senha
            $table->timestamp('created_at')->nullable(); // Data da criação do token (pode ser nulo)
        });

        // Cria a tabela 'sessions' para armazenar sessões ativas dos usuários
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Identificador único da sessão
            $table->foreignId('user_id')->nullable()->index(); // ID do usuário associado, pode ser nulo
            $table->string('ip_address', 45)->nullable(); // Endereço IP da sessão (IPv4 ou IPv6)
            $table->text('user_agent')->nullable(); // Informações do navegador/cliente
            $table->longText('payload'); // Dados da sessão serializados
            $table->integer('last_activity')->index(); // Timestamp da última atividade
        });
    }

    /**
     * Reverte as migrations, removendo as tabelas criadas.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
