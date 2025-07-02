<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a realizar esta requisição.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Sempre retorna true para permitir o login
        return true;
    }

    /**
     * Define as regras de validação aplicadas na requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Tenta autenticar as credenciais fornecidas pelo usuário.
     *
     * @throws \Illuminate\Validation\ValidationException se as credenciais forem inválidas
     *
     * Verifica se a requisição não está bloqueada por excesso de tentativas
     * e executa a tentativa de login com email e senha.
     * Caso falhe, incrementa o contador de tentativas e lança erro de validação.
     * Se sucesso, limpa o contador de tentativas.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Incrementa o número de tentativas para limitar brute-force
            RateLimiter::hit($this->throttleKey());

            // Lança exceção com mensagem genérica de credenciais incorretas
            throw ValidationException::withMessages([
                'email' => 'As credenciais estão incorretas.',
            ]);
        }

        // Limpa o contador de tentativas após login bem-sucedido
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Garante que o usuário não excedeu o limite de tentativas de login.
     *
     * @throws \Illuminate\Validation\ValidationException se a taxa limite for excedida
     *
     * Dispara evento Lockout e retorna mensagem com tempo restante de bloqueio.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            // Ainda não excedeu limite de tentativas
            return;
        }

        // Evento disparado quando bloqueio por excesso de tentativas ocorre
        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        // Exceção com mensagem personalizada de bloqueio (tempo restante)
        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Gera a chave usada para controle da taxa limite (rate limiting).
     *
     * Combina o e-mail em minúsculo transliterado com o IP da requisição.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
