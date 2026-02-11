<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success border-0 mb-4 small">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-600 small">Adresse Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label fw-600 small">Mot de passe</label>
                @if (Route::has('password.request'))
                    <a class="text-primary-link small" href="{{ route('password.request') }}">
                        Oublié ?
                    </a>
                @endif
            </div>
            <input id="password" class="form-control @error('password') is-invalid @enderror"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check mb-4">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label small text-secondary">Se souvenir de moi</label>
        </div>

        <button type="submit" class="btn btn-primary shadow-sm mb-4">
            Se connecter
        </button>

        <div class="text-center small text-secondary">
            Pas encore de compte ? <a href="{{ route('register') }}" class="text-primary-link">Créer un compte</a>
        </div>
    </form>
</x-guest-layout>
