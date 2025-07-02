<nav class="navbar navbar-expand-lg border-bottom bg-body shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <x-application-logo class="me-2 text-secondary" style="height: 20px; width: auto;" />
            <span class="fw-bold text-secondary">Sistema de Autenticação</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @auth
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link text-secondary @if(request()->routeIs('dashboard')) border-bottom border-3 border-primary fw-bold text-primary @endif"
                        href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>
            </ul>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(filled(Auth::user()->profile_photo))
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto do usuário"
                        class="rounded-circle me-2" style="height: 32px; width: 32px; object-fit: cover;">
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                        class="bi bi-person-circle me-2 text-secondary" viewBox="0 0 16 16">
                        <path d="M13.468 12.37C12.758 11.226 11.383 10.5 9.999 10.5c-1.384 0-2.759.726-3.469 1.87C5.42 13.21 5 14.317 5 15.5h10c0-1.183-.42-2.29-1.532-3.13z" />
                        <path fill-rule="evenodd" d="M8 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        <path fill-rule="evenodd" d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0z" />
                    </svg>
                    @endif
                    <span class="me-2">{{ Auth::user()->name }}</span>

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                        <path d="M7.247 11.14 2.451 5.658c-.566-.653.106-1.658.808-1.658h9.482c.702 0 1.374 1.005.808 1.658l-4.796 5.482a1 1 0 0 1-1.516 0z" />
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Perfil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Sair</button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
            <button class="btn btn-outline-secondary position-absolute border-0 top-0 end-0 m-2" onclick="toggleTheme()">
                🌓
            </button>
        </div>
    </div>
</nav>
<script>
    function toggleTheme() {
        const html = document.documentElement;
        const current = html.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        sessionStorage.setItem('theme', next);
    }
</script>