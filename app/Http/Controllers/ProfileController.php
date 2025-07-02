<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Exibe o formulário de edição do perfil do usuário autenticado.
     *
     * @param Request $request
     * @return View
     *
     * Retorna a view 'profile.edit' com os dados do usuário atual.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Atualiza as informações do perfil do usuário autenticado.
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     *
     * Valida os dados recebidos, atualiza o modelo do usuário com nome, e-mail,
     * senha (caso informada) e imagem de perfil, e salva as alterações.
     * Redireciona de volta para a tela de edição com status de sucesso.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        // Validação manual adicional para reforçar regras específicas
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|confirmed|min:8',
                'profile_photo' => 'nullable|image|max:2048',
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O nome deve ser um texto.',
                'name.max' => 'O nome não pode ter mais que 255 caracteres.',

                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'O formato do e-mail deve ser válido.',
                'email.max' => 'O e-mail não pode ter mais que 255 caracteres.',
                'email.unique' => 'Este e-mail já está cadastrado.',

                'password.nullable' => 'O campo senha pode ser deixado em branco.',
                'password.confirmed' => 'A confirmação da senha não confere.',
                'password.min' => 'A senha deve conter no mínimo :min caracteres.',

                'profile_photo.image' => 'O arquivo deve ser uma imagem.',
                'profile_photo.max' => 'A imagem não pode ser maior que 2 MB.',
                'profile_photo.nullable' => 'O campo pode ficar vazio.',
            ]
        );

        // Atualiza os campos permitidos pelo ProfileUpdateRequest
        $request->user()->fill($request->validated());

        // Verifica e processa imagem de perfil (upload)
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        // Atribui nome e e-mail (mesmo se forem iguais)
        $user->name = $request->name;
        $user->email = $request->email;

        // Atualiza senha somente se for fornecida
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Redireciona com status de sucesso
        return Redirect::route('profile.edit')->with('status', 'Salvo perfil');
    }

    /**
     * Exclui a conta do usuário autenticado.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * Valida a senha atual, realiza logout, remove o usuário do banco,
     * invalida a sessão e redireciona para a página inicial.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Valida a senha atual usando o bag de erros 'userDeletion'
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'O campo senha é obrigatório.',
            'password.current_password' => 'A senha atual está incorreta.',
        ]);

        $user = $request->user();

        // Realiza logout antes de excluir a conta
        Auth::logout();

        // Deleta o registro do usuário
        $user->delete();

        // Invalida sessão atual e gera novo token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redireciona para a página principal
        return Redirect::to('/');
    }
}
