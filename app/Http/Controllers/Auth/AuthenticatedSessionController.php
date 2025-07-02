<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Exibe a página de login para o usuário.
     *
     * @return View - Retorna a view 'auth.login' onde o usuário insere suas credenciais.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Processa a solicitação de autenticação do usuário.
     *
     * - Valida e autentica o usuário utilizando a classe LoginRequest.
     * - Regenera a sessão para evitar fixação de sessão (session fixation).
     * - Redireciona o usuário para a rota 'dashboard' ou para a URL originalmente solicitada.
     *
     * @param LoginRequest $request - Requisição validada contendo os dados de login.
     * @return RedirectResponse - Redirecionamento após login bem-sucedido.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Encerra a sessão autenticada do usuário.
     *
     * - Realiza logout da guarda 'web'.
     * - Invalida a sessão atual para limpar dados sensíveis.
     * - Regenera o token CSRF para segurança.
     * - Redireciona o usuário para a página inicial ('/').
     *
     * @param Request $request - Requisição atual.
     * @return RedirectResponse - Redireciona para a página inicial após logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
