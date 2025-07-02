<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class AutenticacaoTeste extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa se um usuário pode se registrar com dados válidos.
     *
     * Verifica redirecionamento, persistência no banco de dados
     * e sucesso no fluxo de registro.
     */
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Teste User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /**
     * Testa se um usuário pode fazer login com credenciais válidas.
     *
     * Valida autenticação e redirecionamento ao dashboard.
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Testa se o usuário autenticado pode editar o nome e e-mail do perfil.
     *
     * Não realiza alteração de senha.
     */
    public function test_user_can_edit_profile()
    {
        $user = User::factory()->create([
            'name' => 'Antigo Nome',
            'email' => 'edit@example.com',
            'password' => Hash::make('oldpass123'),
        ]);

        $this->actingAs($user);

        $response = $this->patch('/profile', [
            'name' => 'Novo Nome',
            'email' => 'edit@example.com',
            'password' => '', // não muda
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', ['name' => 'Novo Nome']);
    }

    /**
     * Testa se o sistema rejeita registro com senha fraca.
     *
     * Deve retornar erro de validação para o campo 'password'.
     */
    public function test_registration_fails_with_weak_password()
    {
        $response = $this->post('/register', [
            'name' => 'Fraca',
            'email' => 'fraca@example.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * Testa se o sistema impede o registro com e-mail já existente.
     *
     * Deve retornar erro de validação para o campo 'email'.
     */
    public function test_registration_fails_with_duplicate_email()
    {
        $user = User::factory()->create(['email' => 'duplicado@example.com']);

        $response = $this->post('/register', [
            'name' => 'Novo',
            'email' => 'duplicado@example.com',
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Testa se o login falha com senha incorreta.
     *
     * Garante que o usuário não é autenticado e é retornado erro de sessão.
     */
    public function test_login_fails_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'fail@example.com',
            'password' => bcrypt('senha123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'fail@example.com',
            'password' => 'errada123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /**
     * Testa se um usuário não autenticado (guest) é redirecionado ao acessar o dashboard.
     *
     * Redireciona para a rota de login.
     */
    public function test_guest_is_redirected_from_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Testa se um usuário autenticado consegue atualizar a senha.
     *
     * Valida a senha antiga e verifica se a nova senha foi salva com sucesso.
     */
    public function test_user_can_update_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('senha_antiga'),
        ]);

        $this->actingAs($user);

        $response = $this->put('/password', [
            'current_password' => 'senha_antiga',
            'password' => 'novasenha123',
            'password_confirmation' => 'novasenha123',
        ]);

        $response->assertRedirect('/');
        $this->assertTrue(Hash::check('novasenha123', $user->fresh()->password));
    }

    /**
     * Testa se o logout funciona corretamente para usuários autenticados.
     *
     * Encerra a sessão e redireciona para a página inicial.
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
