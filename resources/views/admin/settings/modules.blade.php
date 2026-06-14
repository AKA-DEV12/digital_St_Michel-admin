@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-5 animate-fade-in">
        <div>
            <h1 class="h3 fw-bold mb-1">Visibilité des Modules</h1>
            <p class="text-secondary">Activez ou désactivez l'accès et la visibilité des grands modules sur le portail public.</p>
        </div>
    </div>

    <form action="{{ route('admin.modules.update') }}" method="POST" class="animate-fade-in" style="animation-delay: 0.1s">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <div class="col-12 col-md-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <h5 class="fw-bold mb-4 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-cubes text-primary"></i> Statut des modules du portail public
                    </h5>

                    <div class="space-y-4">
                        <!-- Module 1: Réservation Salle -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center text-primary" style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-calendar-check fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Réservation Salle</h6>
                                    <small class="text-secondary">Permet aux fidèles de réserver des salles en ligne pour leurs évènements.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input cursor-pointer" type="checkbox" name="module_booking_enabled" id="module_booking_enabled" value="1" {{ ($settings['module_booking_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <!-- Module 2: Inscriptions -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center text-success" style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-user-plus fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Inscriptions</h6>
                                    <small class="text-secondary">Permet l'inscription en ligne aux pèlerinages et diverses activités paroissiales.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input cursor-pointer" type="checkbox" name="module_registrations_enabled" id="module_registrations_enabled" value="1" {{ ($settings['module_registrations_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <!-- Module 3: Rendez-vous -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center text-info" style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-cross fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Rendez-vous</h6>
                                    <small class="text-secondary">Permet la prise de rendez-vous en ligne avec les pères spirituels de la paroisse.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input cursor-pointer" type="checkbox" name="module_priests_enabled" id="module_priests_enabled" value="1" {{ ($settings['module_priests_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <!-- Module 4: Demande Messe -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center text-warning" style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-hands-praying fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Demande Messe</h6>
                                    <small class="text-secondary">Permet aux paroissiens de formuler et payer leurs intentions de messe en ligne.</small>
                                </div>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input cursor-pointer" type="checkbox" name="module_mass_requests_enabled" id="module_mass_requests_enabled" value="1" {{ ($settings['module_mass_requests_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-primary rounded-3 px-5 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-save me-2"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
                    <h5 class="fw-bold mb-4">Fonctionnement</h5>
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <i class="fa-solid fa-circle-info me-2"></i> Lorsqu'un module est désactivé :
                    </div>
                    <ul class="text-secondary small ps-3 space-y-2" style="list-style-type: disc;">
                        <li class="mb-2">La carte correspondante disparaît de la page d'accueil du portail public.</li>
                        <li class="mb-2">L'accès direct aux URLs du module redirige automatiquement les utilisateurs vers la page d'accueil avec un message d'information.</li>
                    </ul>
                    <div class="mt-auto">
                        <hr class="opacity-10">
                        <p class="text-xs text-secondary mb-0 italic">Dernière synchronisation : {{ date('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
