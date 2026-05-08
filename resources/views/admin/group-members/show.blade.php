@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Détails du membre</h1>
            <p class="text-secondary">Consultez les informations complètes du membre.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('group-members.edit', $groupMember) }}" class="btn btn-primary rounded-3 px-4">
                <i class="fa-solid fa-pen me-2"></i> Modifier
            </a>
            <a href="{{ route('group-members.index') }}" class="btn btn-light rounded-3 px-4">
                <i class="fa-solid fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in mb-4">
            <div class="p-6">
                <div class="d-flex align-items-start gap-4 mb-5">
                    <div class="flex-shrink-0">
                        <img src="{{ $groupMember->photo_url }}" alt="{{ $groupMember->nom_prenom }}" 
                             class="rounded-3" style="width: 120px; height: 120px; object-fit: cover;" 
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\\'rounded-3 bg-gray-100 d-flex align-items-center justify-content-center\\' style=\\'width: 120px; height: 120px;\\'><i class=\\'fa-solid fa-user text-gray-400 fs-2\\'></i></div>';">
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="h4 fw-bold text-dark mb-2">{{ $groupMember->nom_prenom }}</h2>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-calendar text-primary"></i>
                                    <div>
                                        <div class="small text-secondary">Date d'adhésion</div>
                                        <div class="fw-600">{{ $groupMember->formatted_date }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-ring text-primary"></i>
                                    <div>
                                        <div class="small text-secondary">Situation</div>
                                        <div class="fw-600">{{ $groupMember->situation_matrimoniale }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($groupMember->responsabilite)
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <i class="fa-solid fa-user-tie text-primary"></i>
                            <div>
                                <div class="small text-secondary">Responsabilité</div>
                                <div class="fw-600">{{ $groupMember->responsabilite }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <i class="fa-solid fa-users text-primary"></i>
                            <div>
                                <div class="small text-secondary">Mouvements / Groupes</div>
                                <div class="fw-600">
                                    @foreach($groupMember->groups as $group)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 me-1">{{ $group->nom_groupe }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
            <div class="p-4 bg-light border-bottom">
                <h5 class="fw-bold mb-0">
                    <i class="fa-solid fa-history text-primary me-2"></i>
                    Historique
                </h5>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div>
                                <div class="small text-secondary">Création</div>
                                <div class="fw-600">{{ $groupMember->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @if($groupMember->updated_at != $groupMember->created_at)
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-pen"></i>
                            </div>
                            <div>
                                <div class="small text-secondary">Dernière modification</div>
                                <div class="fw-600">{{ $groupMember->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in mb-4">
            <div class="p-4 bg-light border-bottom">
                <h5 class="fw-bold mb-0">
                    <i class="fa-solid fa-gear text-primary me-2"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="p-4">
                <div class="d-grid gap-2">
                    <a href="{{ route('group-members.edit', $groupMember) }}" class="btn btn-primary rounded-3 px-4 py-2">
                        <i class="fa-solid fa-pen me-2"></i> Modifier
                    </a>
                    <form action="{{ route('group-members.destroy', $groupMember) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-3 px-4 py-2 w-100">
                            <i class="fa-solid fa-trash me-2"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
            <div class="p-4 bg-light border-bottom">
                <h5 class="fw-bold mb-0">
                    <i class="fa-solid fa-info-circle text-primary me-2"></i>
                    Résumé
                </h5>
            </div>
            <div class="p-4">
                <div class="space-y-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">ID Membre</span>
                        <span class="fw-600">#{{ $groupMember->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Nom complet</span>
                        <span class="fw-600">{{ $groupMember->nom_prenom }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Date d'adhésion</span>
                        <span class="fw-600">{{ $groupMember->formatted_date }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Situation</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $groupMember->situation_matrimoniale }}</span>
                    </div>
                    @if($groupMember->responsabilite)
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Responsabilité</span>
                        <span class="badge bg-light text-dark small">{{ $groupMember->responsabilite }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Appartenances</span>
                        <span class="fw-600 text-dark small">{{ $groupMember->group_names }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
