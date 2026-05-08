@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="h3 fw-bold mb-0">Détails du Groupe : {{ $group->nom_groupe }}</h1>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Info Card -->
    <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.1s">
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                <i class="fa-solid fa-users"></i>
            </div>
            <h4 class="fw-bold mb-1">
                {{ $group->nom_groupe }}
            </h4>
            <p class="text-secondary mb-4">Groupe n° {{ $group->id }}</p>
            
            <div class="d-flex justify-content-center gap-3 mb-4">
                <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 border-0 shadow-sm fw-bold rounded-pill">Actif</span>
            </div>

            <hr class="my-4 opacity-50">

            <div class="text-start px-3">
                <div class="mb-3">
                    <label class="small text-secondary text-uppercase fw-bold">Nombre de Membres</label>
                    <div class="h4 fw-bold text-primary">{{ $group->members_count ?? 0 }}</div>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary text-uppercase fw-bold">Date de Création</label>
                    <div class="fw-bold small">{{ $group->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="mb-0">
                    <label class="small text-secondary text-uppercase fw-bold">Dernière Mise à Jour</label>
                    <div class="fw-bold small">{{ $group->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: List of group members -->
    @can('access_group_members')
    <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">
                        <i class="fa-solid fa-users me-2 text-secondary"></i> 
                        Membres du Groupe ({{ $group->members->count() }})
                    </h5>
                    <a href="{{ route('group-members.create', ['group_id' => $group->id]) }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-2"></i> Ajouter un membre
                    </a>
                </div>

                @if($group->members->isEmpty())
                    <div class="py-5 text-center text-secondary">
                        <i class="fa-solid fa-user-plus fs-1 mb-3 opacity-25"></i>
                        <p class="mb-0">Ce groupe ne contient aucun membre.</p>
                        <a href="{{ route('group-members.create', ['group_id' => $group->id]) }}" class="btn btn-outline-primary rounded-3 px-4 py-2 fw-bold mt-3">
                            <i class="fa-solid fa-plus me-2"></i> Ajouter le premier membre
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom & Prénom</th>
                                    <th>Date d'adhésion</th>
                                    <th>Responsabilité</th>
                                    <th>Situation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->members as $member)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $member->photo_url }}" alt="{{ $member->nom_prenom }}" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $member->nom_prenom }}</div>
                                                    <div class="small text-secondary">Membre #{{ $member->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-1">
                                                {{ $member->formatted_date }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="small text-secondary">
                                                {{ $member->responsabilite ?: 'Non défini' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1">
                                                {{ $member->situation_matrimoniale }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('group-members.show', $member->id) }}" class="btn btn-outline-primary rounded-3">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{ route('group-members.edit', $member->id) }}" class="btn btn-outline-secondary rounded-3">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s">
        <div class="card border-0 shadow-sm rounded-4 h-100 d-flex align-items-center justify-content-center py-5">
            <div class="text-center p-5">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px; font-size: 1.5rem;">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h5 class="fw-bold text-slate-900">Accès restreint</h5>
                <p class="text-secondary mb-0">Vous n'avez pas les permissions nécessaires pour consulter la liste des membres.</p>
            </div>
        </div>
    </div>
    @endcan
</div>

@endsection
