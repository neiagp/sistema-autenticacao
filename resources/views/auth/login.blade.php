@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-lg border rounded-4">
                <div class="card-header text-center border-0 py-4">
                    <x-application-logo class="me-2" style="height: 40px; width: auto;" />
                    <br><br>
                    <h2>Login</h2>
                </div>

                <div class="card-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <x-form-floating-input
                            name="email"
                            label="E-mail"
                            type="email"
                            :value="old('email')"
                            required
                            autofocus />

                        <x-form-floating-input
                            name="password"
                            label="Senha"
                            type="password"
                            required />

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Lembrar-me</label>
                        </div>

                        <div class="d-grid gap-2 text-center">
                            <x-primary-button>Entrar</x-primary-button>
                            <a href="{{ route('register') }}" class="text-decoration-underline text-secondary">Quero me cadastrar</a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center border-0">
                    <small class="text-body-secondary">&copy; {{ date('Y') }} Minha Aplicação</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection