<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Exibe a view para o usuário redefinir a senha.
     *
     * @param Request $request - Contém os dados da requisição, incluindo o token de reset e e-mail
     * @return View - Retorna a view 'auth.reset-password' com os dados da requisição
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Trata a solicitação de redefinição de senha.
     *
     * Fluxo:
     * - Valida os campos obrigatórios:
     *     - token: obrigatório (para verificação de reset)
     *     - email: obrigatório, formato válido
     *     - password: obrigatório, confirmado, e com regras fortes (min, letras, números, símbolos, etc)
     * - Tenta redefinir a senha do usuário usando a facade Password::reset()
     *     - Se o reset for bem sucedido, atualiza a senha no banco com hash seguro,
     *       gera um novo token de "lembrar-me" e dispara o evento PasswordReset.
     * - Redireciona o usuário para a página de login com mensagem de sucesso ou
     *   volta para o formulário com os erros e dados preenchidos (exceto senha).
     *
     * @param Request $request - Requisição HTTP contendo dados para resetar a senha
     * @return RedirectResponse - Redireciona com status de sucesso ou erro
     *
     * @throws \Illuminate\Validation\ValidationException Em caso de falha na validação dos dados
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação
        $request->validate(
            [
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'token.required' => 'O campo token é obrigatório.',
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Por favor, insira um endereço de e-mail válido.',
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

        // Tenta executar o reset de senha
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Dispara evento para indicar que a senha foi resetada
                event(new PasswordReset($user));
            }
        );

        // Redireciona para a página de login com mensagem adequada ou volta com erros
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
