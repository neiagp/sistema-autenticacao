@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-lg border rounded-4">
                <div class="card-header text-center border-0 py-4">
                    <h2>Cadastrar</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                        @csrf

                        <x-form-floating-input
                            name="name"
                            label="Nome"
                            :value="old('name')"
                            required
                            autofocus
                            autocomplete="name" />

                        <x-form-floating-input
                            name="email"
                            label="E-mail"
                            type="email"
                            :value="old('email')"
                            required
                            autocomplete="username" />

                        <hr />

                        <x-form-floating-input
                            name="password"
                            label="Senha"
                            type="password"
                            required
                            autocomplete="new-password" />

                        <x-form-floating-input
                            name="password_confirmation"
                            label="Confirmar Senha"
                            type="password"
                            required
                            autocomplete="new-password" />

                        <hr />

                        <x-file-input
                            name="profile_photo"
                            label="Foto de perfil"
                            accept="image/*" />

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-underline text-secondary">
                                {{ __('Já tenho registro?') }}
                            </a>

                            <x-primary-button>Registrar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection