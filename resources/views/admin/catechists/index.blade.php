@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold mb-1">Base de données des Catéchistes</h1>
            <p class="text-secondary">Gérez les inscriptions et suivez l'évolution des catéchistes.</p>
        </div>
        <a href="{{ route('admin.catechists.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Ajouter un catéchiste
        </a>
    </div>
</div>

<!-- Filtres de recherche -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.catechists.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label small fw-bold text-slate-700">Recherche</label>
                <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" 
                       id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Nom ou matricule...">
            </div>
            
            <div class="col-md-3">
                <label for="annee_catechese" class="form-label small fw-bold text-slate-700">Année de catéchèse</label>
                <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" 
                        id="annee_catechese" name="annee_catechese">
                    <option value="">Toutes les années</option>
                    <option value="1ere" {{ request('annee_catechese') == '1ere' ? 'selected' : '' }}>1ère année</option>
                    <option value="2eme" {{ request('annee_catechese') == '2eme' ? 'selected' : '' }}>2ème année</option>
                    <option value="3eme" {{ request('annee_catechese') == '3eme' ? 'selected' : '' }}>3ème année</option>
                    <option value="4eme" {{ request('annee_catechese') == '4eme' ? 'selected' : '' }}>4ème année</option>
                    <option value="5eme" {{ request('annee_catechese') == '5eme' ? 'selected' : '' }}>5ème année</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="statut_catechese" class="form-label small fw-bold text-slate-700">Statut</label>
                <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" 
                        id="statut_catechese" name="statut_catechese">
                    <option value="">Tous les statuts</option>
                    <option value="En cours" {{ request('statut_catechese') == 'En cours' ? 'selected' : '' }}>En cours</option>
                    <option value="Terminee" {{ request('statut_catechese') == 'Terminee' ? 'selected' : '' }}>Terminée</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label small fw-bold text-slate-700">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 px-3 py-2 fw-bold flex-fill">
                        <i class="fa-solid fa-search"></i>
                    </button>
                    <a href="{{ route('admin.catechists.index') }}" class="btn btn-light rounded-3 px-3 py-2 fw-bold">
                        <i class="fa-solid fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

    <x-data-table :headers="['Photo', 'Nom & Prénom', 'Année Catéchèse', 'Matricule', 'Action']" :collection="$catechists">
        <x-slot name="title">Liste des Catéchistes</x-slot>

        @foreach($catechists as $catechist)
            <tr>
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-container rounded-circle overflow-hidden border-2 border-slate-200 shadow-sm" style="width: 48px; height: 48px;">
                            @if($catechist->photo)
                                <img src="{{ $catechist->photo_url }}" 
                                     alt="{{ $catechist->nom_prenom }}" 
                                     class="w-100 h-100 object-fit-cover"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($catechist->nom_prenom) }}&color=7F9CF5&background=EBF4FF'">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold">
                                    {{ strtoupper(substr($catechist->nom_prenom, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark h6 mb-0">{{ $catechist->nom_prenom }}</div>
                    @if($catechist->age)
                        <div class="small text-secondary">{{ $catechist->age }} ans</div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-info bg-opacity-10 text-info fw-bold px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-graduation-cap me-1"></i>
                            {{ $catechist->annee_catechese }}
                        </span>
                        @if($catechist->statut_catechese === 'Terminee')
                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill">
                                <i class="fa-solid fa-check-circle me-1"></i> Terminé
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="badge bg-slate-900 text-white fw-bold px-3 py-2 rounded-3">
                        {{ $catechist->matricule }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.catechists.show', $catechist->id) }}" 
                           class="btn btn-sm btn-light border-0 text-primary fw-bold rounded-pill px-3"
                           title="Voir les détails">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.catechists.edit', $catechist->id) }}" 
                           class="btn btn-sm btn-light border-0 text-warning fw-bold rounded-pill px-3"
                           title="Modifier">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.catechists.destroy', $catechist->id) }}" 
                              method="POST" class="d-inline" 
                              onsubmit="return confirm('Supprimer définitivement ce catéchiste ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border-0 text-danger fw-bold rounded-pill px-3"
                                    title="Supprimer">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>

@endsection
