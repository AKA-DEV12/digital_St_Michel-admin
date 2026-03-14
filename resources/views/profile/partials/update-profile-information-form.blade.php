<section>
    <form method="post" action="{{ route('profile.update') }}" class="mt-2">
        @csrf
        @method('patch')

        <div class="row g-4">
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="name" class="form-label fw-bold small text-uppercase opacity-75">Nom Complet</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa-regular fa-user text-muted"></i></span>
                        <input id="name" name="name" type="text" class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="email" class="form-label fw-bold small text-uppercase opacity-75">Adresse Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa-regular fa-envelope text-muted"></i></span>
                        <input id="email" name="email" type="email" class="form-control bg-light border-0 py-2 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning border-0 rounded-4 d-flex align-items-center gap-3 mb-4">
                <i class="fa-solid fa-circle-exclamation fs-4"></i>
                <div class="flex-grow-1">
                    <p class="mb-0 small fw-600">Votre adresse email n'est pas vérifiée.</p>
                </div>
                <button form="send-verification" class="btn btn-sm btn-dark rounded-pill px-3">
                    Renvoyer l'email
                </button>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success border-0 rounded-4 mb-4 small fw-600">
                    Un nouveau lien de vérification a été envoyé.
                </div>
            @endif
        @endif

        <div class="d-flex align-items-center gap-3 py-3 border-top mt-4">
            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">
                <i class="fa-solid fa-check me-2"></i> Enregistrer les modifications
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small fw-600 animate-fade-in">
                    <i class="fa-solid fa-circle-check me-1"></i> Modifications enregistrées avec succès.
                </span>
            @endif
        </div>
    </form>
</section>
