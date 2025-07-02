<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Envia uma nova notificação de verificação de e-mail para o usuário autenticado.
     *
     * - Verifica se o e-mail do usuário já está verificado:
     *     - Se sim, redireciona para a rota 'dashboard'.
     *     - Caso contrário, envia a notificação de verificação.
     * - Retorna para a página anterior com uma mensagem de status informando
     *   que o link de verificação foi enviado.
     *
     * @param Request $request - Requisição HTTP atual com usuário autenticado.
     * @return RedirectResponse - Redirecionamento para dashboard ou retorno à página anterior com status.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
