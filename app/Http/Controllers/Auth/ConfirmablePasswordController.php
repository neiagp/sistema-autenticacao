<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Exibe a página para o usuário confirmar sua senha.
     *
     * @return View - Retorna a view 'auth.confirm-password' para o usuário digitar a senha.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Valida a senha informada pelo usuário.
     *
     * - Verifica se a senha fornecida corresponde à senha do usuário autenticado.
     * - Caso a validação falhe, lança uma exceção de validação com a mensagem apropriada.
     * - Se a senha estiver correta, armazena na sessão o timestamp da confirmação.
     * - Redireciona o usuário para a rota 'dashboard' ou para a URL pretendida.
     *
     * @param Request $request - Requisição contendo a senha para validação.
     * @throws ValidationException - Se a senha estiver incorreta.
     * @return RedirectResponse - Redirecionamento após confirmação bem-sucedida.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        // Registra o momento em que a senha foi confirmada para evitar solicitações repetidas
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
