<section>
    <header>
        <h2 class="text-lg font-medium">
            Informações do perfil
        </h2>

        <p class="mt-1 text-sm">
            Atualize as informações do perfil, endereço de e-mail da sua conta e a sua foto
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        @if (session('status') === 'Salvo perfil')
        <x-success-alert>
            Seus dados foram atualizados com sucesso!
        </x-success-alert>
        @endif

        <x-form-floating-input
            name="name"
            label="Nome"
            :value="old('name', auth()->user()->name)"
            required
            autofocus
            autocomplete="name" />

        <x-form-floating-input
            name="email"
            label="E-mail"
            type="email"
            :value="old('email', auth()->user()->email)"
            required
            autocomplete="username" />
            
        <x-file-input-with-preview
            name="profile_photo"
            label="Foto de perfil"
            accept="image/*"
            :currentImage="auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : null" />

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>
        </div>
    </form>
</section>