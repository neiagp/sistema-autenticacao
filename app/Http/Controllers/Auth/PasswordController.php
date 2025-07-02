<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Atualiza a senha do usuário autenticado.
     *
     * Fluxo:
     * - Valida os campos 'current_password' e 'password' com regras específicas.
     *   - 'current_password' deve ser obrigatório e igual à senha atual do usuário.
     *   - 'password' deve ser obrigatório, confirmado (campo *_confirmation), e seguir regras de segurança (mínimo, letras, números, símbolos, etc).
     * - Caso a validação passe, atualiza a senha do usuário no banco, aplicando hash seguro.
     * - Retorna um redirect para a mesma página com mensagem de sucesso.
     *
     * @param Request $request - Requisição HTTP contendo os dados do formulário
     * @return RedirectResponse - Redireciona de volta com status de sucesso
     *
     * @throws \Illuminate\Validation\ValidationException Caso a validação falhe
     */
    public function update(Request $request): RedirectResponse
    {
        // Validação com bag específica para erros do update de senha
        $validated = $request->validateWithBag(
            'updatePassword',
            [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ],
            [
                'current_password.required' => 'A senha atual é obrigatória.',
                'current_password.current_password' => 'A senha atual está incorreta.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.confirmed' => 'A confirmação da senha não confere.',
                'password.min' => 'A senha deve conter no mínimo :min caracteres.',
                'password.letters' => 'A senha deve conter pelo menos uma letra.',
                'password.mixed' => 'A senha deve conter letras maiúsculas e minúsculas.',
                'password.numbers' => 'A senha deve conter pelo menos um número.',
                'password.symbols' => 'A senha deve conter pelo menos um símbolo.',
                'password.uncompromised' => 'Esta senha apareceu em um vazamento de dados. Escolha outra senha.',
            ]
        );

        // Atualiza a senha do usuário aplicando hash seguro
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redireciona de volta com mensagem de sucesso
        return back()->with('status', 'Senha salva');
    }
}
