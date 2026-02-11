<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-600 small">Nom complet</label>
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-600 small">Adresse Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-600 small">Mot de passe</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-600 small">Confirmer le mot de passe</label>
            <input id="password_confirmation" class="form-control"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
        </div>

        <button type="submit" class="btn btn-primary shadow-sm mb-4">
            Créer un compte
        </button>

        <div class="text-center small text-secondary">
            Déjà inscrit ? <a href="{{ route('login') }}" class="text-primary-link">Se connecter</a>
        </div>
    </form>
</x-guest-layout>
