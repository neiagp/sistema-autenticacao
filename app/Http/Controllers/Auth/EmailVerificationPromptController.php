<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Exibe a view de solicitação de verificação de e-mail ou redireciona
     * para o dashboard caso o e-mail já esteja verificado.
     *
     * @param Request $request - Objeto da requisição HTTP atual, usado para
     *                          acessar o usuário autenticado
     * @return RedirectResponse|View - Redireciona para dashboard se o e-mail
     *                                estiver verificado, caso contrário retorna
     *                                a view para verificação de e-mail
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
