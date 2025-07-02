<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Marca o e-mail do usuário autenticado como verificado.
     *
     * Este método é invocado automaticamente pela rota de verificação de e-mail.
     *
     * Passos:
     * - Verifica se o e-mail já foi verificado; se sim, redireciona ao dashboard com flag 'verified=1'.
     * - Caso contrário, marca o e-mail como verificado no banco de dados.
     * - Dispara o evento Verified para acionar qualquer ação pós-verificação (ex: notificações).
     * - Redireciona para o dashboard com query string indicando sucesso na verificação.
     *
     * @param EmailVerificationRequest $request - Requisição validada com o hash de verificação
     * @return RedirectResponse - Redireciona para o dashboard com status de verificação
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // E-mail já verificado, apenas redireciona
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            // Marca o e-mail como verificado e dispara o evento
            event(new Verified($request->user()));
        }

        // Redireciona ao dashboard com indicação de verificação concluída
        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
