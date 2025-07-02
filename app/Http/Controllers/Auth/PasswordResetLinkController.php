<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Exibe a view para o usuário solicitar o link de redefinição de senha.
     *
     * @return View
     *
     * Renderiza a página 'auth.forgot-password' com o formulário para inserir o e-mail.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Processa o envio do link de redefinição de senha.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * Valida o e-mail informado, tenta enviar o link de recuperação usando o serviço
     * Password do Laravel e retorna uma resposta adequada:
     * - Se o link foi enviado com sucesso, retorna para a mesma página com mensagem de sucesso.
     * - Se houve erro (ex: e-mail não encontrado), retorna com erros e mantém o e-mail preenchido.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação simples do e-mail (obrigatório e formato válido)
        $request->validate(
            [
                'email' => ['required', 'email'],
            ],
            [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Por favor, insira um endereço de e-mail válido.',
            ]
        );

        // Tenta enviar o link de redefinição de senha para o e-mail informado
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Retorna a resposta adequada para o usuário
        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
