@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('admin.catechists.index') }}" class="text-secondary text-decoration-none fw-bold mb-3 d-inline-block">
                <i class="fa-solid fa-arrow-left me-2"></i> Retour aux catéchistes
            </a>
            <h1 class="h3 fw-bold mb-1">Détails du Catéchiste</h1>
            <p class="text-secondary">Consultez toutes les informations sur {{ $catechist->nom_prenom }}.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.catechists.edit', $catechist->id) }}" 
               class="btn btn-warning rounded-3 px-4 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-edit me-2"></i> Modifier
            </a>
            <form action="{{ route('admin.catechists.destroy', $catechist->id) }}" 
                  method="POST" class="d-inline" 
                  onsubmit="return confirm('Supprimer définitivement ce catéchiste ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger rounded-3 px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-trash me-2"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations principales -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center p-5">
                <img src="{{ $catechist->photo_url }}" 
                     alt="{{ $catechist->nom_prenom }}" 
                     class="rounded-circle border-3 border-primary mb-3 mx-auto"
                     style="width: 120px; height: 120px; object-fit: cover;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($catechist->nom_prenom) }}&color=7F9CF5&background=EBF4FF&size=120'">
                
                <h4 class="fw-bold text-dark mb-1">{{ $catechist->nom_prenom }}</h4>
                <div class="mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary fw-bold fs-6">
                        {{ $catechist->matricule }}
                    </span>
                </div>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($catechist->statut_catechese === 'En cours')
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold">
                            <i class="fa-solid fa-clock me-1"></i>En cours
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold">
                            <i class="fa-solid fa-check me-1"></i>Terminée
                        </span>
                    @endif
                    <span class="badge bg-info bg-opacity-10 text-info fw-bold">
                        {{ $catechist->annee_catechese }}
                    </span>
                </div>
                
                @if($catechist->age)
                    <div class="text-muted small mb-2">
                        <i class="fa-solid fa-calendar me-1"></i>{{ $catechist->age }} ans
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informations détaillées -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <h5 class="fw-bold mb-4 text-slate-800">Informations personnelles</h5>
                
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-slate-700">Date de naissance</label>
                        <div class="text-dark">
                            @if($catechist->date_naissance)
                                {{ $catechist->date_naissance->format('d/m/Y') }}
                            @else
                                <span class="text-muted">Non renseignée</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-slate-700">Lieu d'habitation</label>
                        <div class="text-dark">
                            {{ $catechist->lieu_habitation ?: '<span class="text-muted">Non renseigné</span>' }}
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-slate-700">Situation matrimoniale</label>
                        <div class="text-dark">
                            {{ $catechist->situation_matrimoniale ?: '<span class="text-muted">Non renseignée</span>' }}
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="small fw-bold text-slate-700">Nombre d'enfants</label>
                        <div class="text-dark">
                            {{ $catechist->nombre_enfant ?: '<span class="text-muted">0</span>' }}
                        </div>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label class="small fw-bold text-slate-700">Situation professionnelle</label>
                        <div class="text-dark">
                            {{ $catechist->situation_professionnelle ?: '<span class="text-muted">Non renseignée</span>' }}
                        </div>
                    </div>
                </div>

                <!-- Antécédents catéchèse -->
                <h5 class="fw-bold mb-3 text-slate-800">Antécédents en catéchèse</h5>
                @if($catechist->antecedent)
                    <div class="bg-info bg-opacity-10 rounded-3 p-3 mb-4">
                        <div class="row">
                            @if($catechist->antecedent_date)
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold text-slate-700">Date</label>
                                    <div class="text-dark">{{ $catechist->antecedent_date->format('d/m/Y') }}</div>
                                </div>
                            @endif
                            
                            @if($catechist->antecedent_annee_catechese)
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold text-slate-700">Année</label>
                                    <div class="text-dark">{{ $catechist->antecedent_annee_catechese }}</div>
                                </div>
                            @endif
                            
                            @if($catechist->antecedent_paroisse)
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold text-slate-700">Paroisse</label>
                                    <div class="text-dark">{{ $catechist->antecedent_paroisse }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-muted mb-4">Aucun antécédent en catéchèse</div>
                @endif

                <!-- Appartenance groupe -->
                <h5 class="fw-bold mb-3 text-slate-800">Appartenance à un groupe/mouvement</h5>
                @if($catechist->groupe_mouvement && $catechist->group)
                    <div class="bg-primary bg-opacity-10 rounded-4 p-4 mb-4 shadow-sm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-slate-700 text-uppercase" style="letter-spacing: 0.5px;">Groupe / Mouvement</label>
                                <div class="text-dark fw-bold h5 mb-0">{{ $catechist->group->nom_groupe }}</div>
                            </div>
                            
                            @if($catechist->member)
                                <div class="col-md-6 mb-3">
                                    <label class="small fw-bold text-slate-700 text-uppercase" style="letter-spacing: 0.5px;">Date d'adhésion</label>
                                    <div class="text-dark fw-semibold">
                                        <i class="fa-solid fa-calendar-day text-primary me-2"></i>
                                        {{ $catechist->member->date_adhesion ? $catechist->member->date_adhesion->format('d/m/Y') : 'Non renseignée' }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="small fw-bold text-slate-700 text-uppercase" style="letter-spacing: 0.5px;">Responsabilité</label>
                                    <div class="text-dark fw-semibold">
                                        <i class="fa-solid fa-user-tag text-primary me-2"></i>
                                        {{ $catechist->member->responsabilite ?: 'Membre simple' }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-slate-50 border border-dashed rounded-4 p-4 text-center mb-4">
                        <p class="text-secondary mb-0 fst-italic">N'appartient à aucun groupe ou mouvement paroissial.</p>
                    </div>
                @endif

                <!-- Informations baptême -->
                <h5 class="fw-bold mb-3 text-slate-800">Informations sur le baptême</h5>
                @if($catechist->baptiser)
                    <div class="bg-success bg-opacity-10 rounded-3 p-3 mb-4">
                        <div class="row">
                            @if($catechist->date_bapteme)
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold text-slate-700">Date du baptême</label>
                                    <div class="text-dark">{{ $catechist->date_bapteme->format('d/m/Y') }}</div>
                                </div>
                            @endif
                            
                            @if($catechist->paroisse_bapteme)
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold text-slate-700">Paroisse du baptême</label>
                                    <div class="text-dark">{{ $catechist->paroisse_bapteme }}</div>
                                </div>
                            @endif
                            
                            @if($catechist->carnet_bapteme)
                                <div class="col-md-4 mb-2">
                                    <label class="small fw-bold text-slate-700">Numéro carnet</label>
                                    <div class="text-dark">{{ $catechist->carnet_bapteme }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-muted mb-4">Non baptisé(e)</div>
                @endif

                <!-- Informations système -->
                <h5 class="fw-bold mb-3 text-slate-800">Informations système</h5>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="small fw-bold text-slate-700">Créé le</label>
                        <div class="text-dark">{{ $catechist->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-2">
                        <label class="small fw-bold text-slate-700">Dernière modification</label>
                        <div class="text-dark">{{ $catechist->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    @if($catechist->creator)
                        <div class="col-12 mb-2">
                            <label class="small fw-bold text-slate-700">Créé par</label>
                            <div class="text-dark">{{ $catechist->creator->name }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
