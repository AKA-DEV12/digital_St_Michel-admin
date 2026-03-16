@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Modifier la Publicité</h1>
                <p class="text-secondary">Mettez à jour les informations de la publicité.</p>
            </div>
            <a href="{{ route('admin.ads.index') }}" class="btn btn-white border border-gray-100 rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Annuler
            </a>
        </div>
    </div>

    <form action="{{ route('admin.ads.update', $ad) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4 animate-fade-in" style="animation-delay: 0.1s">
            <div class="col-12 col-lg-8">
                <div class="card border-0 rounded-4 shadow-sm p-4 h-100 bg-white">
                    <div class="mb-4">
                        <label for="title" class="form-label small fw-bold text-secondary">Titre de la publicité</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg rounded-3 border-gray-200 fw-bold" value="{{ old('title', $ad->title) }}" required>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="link_url" class="form-label small fw-bold text-secondary">Lien de destination (URL)</label>
                        <input type="url" name="link_url" id="link_url" class="form-control rounded-3 border-gray-200" value="{{ old('link_url', $ad->link_url) }}">
                        @error('link_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="order" class="form-label small fw-bold text-secondary">Ordre d'affichage</label>
                            <input type="number" name="order" id="order" class="form-control rounded-3 border-gray-200" value="{{ old('order', $ad->order) }}">
                        </div>
                        <div class="col-md-6 mb-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-secondary ms-2" for="is_active">Publicité active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mb-4">
                    <h6 class="fw-bold mb-4">Image Publicitaire</h6>
                    
                    <div class="mb-3 rounded-3 overflow-hidden shadow-sm border bg-light d-flex align-items-center justify-content-center" style="min-height: 150px;">
                        @if($ad->image)
                            <img src="{{ asset('storage/' . $ad->image) }}" class="w-100 h-auto" alt="Aperçu">
                        @else
                            <i class="fa-solid fa-image fs-1 text-secondary opacity-30"></i>
                        @endif
                    </div>

                    <input type="file" name="image" id="image" class="form-control rounded-3 border-gray-200">
                    <p class="text-secondary x-small mt-2 mb-0">Laissez vide pour conserver l'image actuelle.</p>
                    @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-3 fw-bold shadow-sm">
                        <i class="fa-solid fa-save me-2"></i> Mettre à jour
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
