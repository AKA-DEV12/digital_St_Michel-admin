<section>
    <form method="post" action="{{ route('password.update') }}" class="mt-2">
        @csrf
        @method('put')

        <div class="row g-4">
            <div class="col-12">
                <div class="form-group mb-4">
                    <label for="update_password_current_password" class="form-label fw-bold small text-uppercase opacity-75">Mot de passe actuel</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa-solid fa-key text-muted"></i></span>
                        <input id="update_password_current_password" name="current_password" type="password" class="form-control bg-light border-0 py-2 @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                    </div>
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="update_password_password" class="form-label fw-bold small text-uppercase opacity-75">Nouveau mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input id="update_password_password" name="password" type="password" class="form-control bg-light border-0 py-2 @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    </div>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="update_password_password_confirmation" class="form-label fw-bold small text-uppercase opacity-75">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock-open text-muted"></i></span>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control bg-light border-0 py-2 @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 py-3 border-top mt-4">
            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold">
                <i class="fa-solid fa-shield-halved me-2"></i> Mettre à jour le mot de passe
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success small fw-600 animate-fade-in">
                    <i class="fa-solid fa-circle-check me-1"></i> Mot de passe mis à jour avec succès.
                </span>
            @endif
        </div>
    </form>
</section>
