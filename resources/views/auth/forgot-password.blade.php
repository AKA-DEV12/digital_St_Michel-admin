<x-guest-layout>
    <div class="mb-4 small text-secondary">
        Mot de passe oublié ? Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success border-0 mb-4 small">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label fw-600 small">Adresse Email</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary shadow-sm mb-4">
            Envoyer le lien de réinitialisation
        </button>

        <div class="text-center small">
            <a href="{{ route('login') }}" class="text-primary-link">Retour à la connexion</a>
        </div>
    </form>
</x-guest-layout>
