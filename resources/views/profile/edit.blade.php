@extends('layouts.app')

@section('content')
<div class="container-fluid animate-fade-in">
    <!-- Header Page -->
    <div class="mb-5">
        <h1 class="fw-bold text-dark mb-1">Mon Profil</h1>
        <p class="text-secondary">Gérez vos informations personnelles et la sécurité de votre compte.</p>
    </div>

    <div class="row g-4">
        <!-- Informations Profil -->
        <div class="col-lg-8">
            <div class="glass-card mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="icon-box bg-primary-light text-primary rounded-3 p-3">
                        <i class="fa-solid fa-user-gear fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">Informations Personnelles</h4>
                        <p class="text-muted small mb-0">Mettez à jour votre nom et votre adresse email.</p>
                    </div>
                </div>
                
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="glass-card" id="password-section">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="icon-box bg-primary-light text-primary rounded-3 p-3">
                        <i class="fa-solid fa-shield-halved fs-4"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">Sécurité du Compte</h4>
                        <p class="text-muted small mb-0">Assurez-vous que votre compte utilise un mot de passe robuste.</p>
                    </div>
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Sidebar Profil / Status -->
        <div class="col-lg-4">
            <div class="glass-card mb-4 bg-primary text-white border-0 shadow-sm" style="background: linear-gradient(135deg, var(--primary) 0%, #be123c 100%) !important;">
                <div class="text-center py-4">
                    <div class="avatar-large mx-auto mb-3 shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="opacity-75 small mb-0">{{ Auth::user()->email }}</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-primary rounded-pill px-3">{{ Auth::user()->roles->first()->name ?? 'Membre' }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card border-danger border-opacity-10 bg-danger bg-opacity-10">
                <div class="d-flex align-items-center gap-2 text-danger mb-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <h6 class="fw-bold mb-0">Zone de Danger</h6>
                </div>
                <p class="text-danger small opacity-75 mb-4">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.</p>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

<style>
    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .avatar-large {
        width: 100px;
        height: 100px;
        background: white;
        color: var(--primary);
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 800;
    }
</style>
@endsection
