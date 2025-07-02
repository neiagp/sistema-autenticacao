<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Exibe a view de registro de novo usuário.
     *
     * @return View
     *
     * Retorna a página de cadastro ('auth.register').
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Processa a requisição de registro de usuário.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * Valida os dados de entrada (nome, e-mail, senha, foto de perfil),
     * cria o usuário no banco de dados, processa upload da foto (se houver),
     * dispara evento Registered para gatilhos adicionais (ex: envio de e-mail),
     * autentica o usuário recém-criado e redireciona para o dashboard.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação dos dados do formulário de registro
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::defaults()],
                'profile_photo' => 'nullable|image|max:2048',
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O nome deve ser um texto.',
                'name.max' => 'O nome não pode ter mais que 255 caracteres.',

                'email.required' => 'O campo e-mail é obrigatório.',
                'email.string' => 'O e-mail deve ser um texto.',
                'email.lowercase' => 'O e-mail deve estar em letras minúsculas.',
                'email.email' => 'O formato do e-mail deve ser válido.',
                'email.max' => 'O e-mail não pode ter mais que 255 caracteres.',
                'email.unique' => 'Este e-mail já está cadastrado.',

                'password.required' => 'O campo senha é obrigatório.',
                'password.confirmed' => 'A confirmação da senha não confere.',
                'password.min' => 'A senha deve conter no mínimo :min caracteres.',
                'password.letters' => 'A senha deve conter pelo menos uma letra.',
                'password.mixed' => 'A senha deve conter letras maiúsculas e minúsculas.',
                'password.numbers' => 'A senha deve conter pelo menos um número.',
                'password.symbols' => 'A senha deve conter pelo menos um símbolo.',
                'password.uncompromised' => 'Esta senha apareceu em um vazamento de dados. Escolha outra senha.',

                'profile_photo.image' => 'O arquivo deve ser uma imagem.',
                'profile_photo.max' => 'A imagem não pode ser maior que 2 MB.',
                'profile_photo.nullable' => 'O campo pode ficar vazio.',
            ]
        );

        // Criação do usuário com os dados validados e senha criptografada
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Processa upload da foto de perfil (se presente)
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        // Dispara evento Registered para outros processos pós-registro (ex: envio de e-mail)
        event(new Registered($user));

        // Autentica automaticamente o usuário recém-criado
        Auth::login($user);

        // Redireciona para rota 'dashboard' após sucesso no registro
        return redirect(route('dashboard', absolute: false));
    }
}
