@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Ajouter un Message Flash</h1>
                <p class="text-secondary">Créez un nouveau message pour le défilement.</p>
            </div>
            <a href="{{ route('admin.flash-messages.index') }}" class="btn btn-white border rounded-3 px-3 py-2 text-secondary fw-600 shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Retour
            </a>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
        <form action="{{ route('admin.flash-messages.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="message" class="form-label fw-bold">Message</label>
                <textarea name="message" id="message" rows="3" class="form-control rounded-3 border-gray-100" required placeholder="Saisissez votre message flash ici..."></textarea>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label fw-bold" for="is_active">Activer ce message</label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary rounded-3 px-5 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-save me-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection
坊
