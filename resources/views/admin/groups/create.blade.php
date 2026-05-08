@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <a href="{{ route('groups.index') }}" class="text-secondary text-decoration-none fw-bold mb-3 d-inline-block">
        <i class="fa-solid fa-arrow-left me-2"></i> Retour aux groupes
    </a>
    <h1 class="h3 fw-bold mb-1">Ajouter un nouveau groupe</h1>
    <p class="text-secondary">Créez un nouveau groupe paroissial pour organiser vos membres.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <h5 class="fw-bold mb-4 text-slate-800">Informations du groupe</h5>
                <form action="{{ route('groups.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nom_groupe" class="form-label small fw-bold text-slate-700">Nom du groupe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="nom_groupe" name="nom_groupe" required placeholder="Ex: Conseil Paroissial">
                        @error('nom_groupe')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('groups.index') }}" class="btn btn-light rounded-3 px-4 py-2 fw-bold">
                            <i class="fa-solid fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-plus me-2"></i> Ajouter le groupe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
