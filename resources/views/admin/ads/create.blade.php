@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Nouvelle Publicité</h1>
                <p class="text-secondary">Ajoutez une image publicitaire pour le slider de la barre latérale.</p>
            </div>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Annuler
            </a>
        </div>
    </div>

    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4 animate-fade-in" style="animation-delay: 0.1s">
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white">
                    <div class="mb-4">
                        <label for="title" class="form-label small fw-bold text-secondary">Titre de la publicité</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg rounded-3 border-gray-200 fw-bold" value="{{ old('title') }}" required placeholder="Ex: Campagne Carême 2026">
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="link_url" class="form-label small fw-bold text-secondary">Lien de destination (URL)</label>
                        <input type="url" name="link_url" id="link_url" class="form-control rounded-3 border-gray-200" value="{{ old('link_url') }}" placeholder="https://exemple.com/article-cible">
                        @error('link_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        <p class="text-secondary x-small mt-2 mb-0 italic">Lien vers lequel l'utilisateur sera redirigé au clic.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="order" class="form-label small fw-bold text-secondary">Ordre d'affichage</label>
                            <input type="number" name="order" id="order" class="form-control rounded-3 border-gray-200" value="{{ old('order', 0) }}">
                        </div>
                        <div class="col-md-6 mb-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-secondary ms-2" for="is_active">Afficher immédiatement</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-4">Image Publicitaire</h6>
                    <input type="file" name="image" id="image" class="form-control rounded-3 border-gray-200" required>
                    <p class="text-secondary x-small mt-2 mb-0">Format recommandé : Rectangulaire (ex: 800x400px). Max 12Mo.</p>
                    @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-3 fw-bold shadow-sm">
                        <i class="fa-solid fa-save me-2"></i> Enregistrer la publicité
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
