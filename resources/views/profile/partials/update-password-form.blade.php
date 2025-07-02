<section>
    <header>
        <h2 class="text-lg font-medium">
            Atualizar Senha
        </h2>

        <p class="mt-1 text-sm">
            Certifique-se de que sua conta esteja usando uma senha longa e aleatória para permanecer segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        @if (session('status') === 'Senha salva')
        <x-success-alert>
            Senha alterada com sucesso!
        </x-success-alert>
        @endif

        <x-form-floating-input
            name="current_password"
            label="Senha atual"
            type="password"
            autocomplete="current-password"
            error-bag="updatePassword"
            required />

        <x-form-floating-input
            name="password"
            label="Nova Senha"
            type="password"
            autocomplete="new-password"
            error-bag="updatePassword"
            required />

        <x-form-floating-input
            name="password_confirmation"
            label="Confirmar Nova Senha"
            type="password"
            autocomplete="new-password"
            error-bag="updatePassword"
            required />

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>
        </div>
    </form>
</section>