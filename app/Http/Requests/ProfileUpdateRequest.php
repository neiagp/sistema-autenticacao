<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Define as regras de validação que se aplicam à requisição de atualização de perfil.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     * 
     * Regras:
     * - name: obrigatório, tipo string, máximo 255 caracteres.
     * - email: obrigatório, string, convertido para lowercase, formato de e-mail válido,
     *   máximo 255 caracteres, único na tabela de usuários exceto para o usuário atual.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
