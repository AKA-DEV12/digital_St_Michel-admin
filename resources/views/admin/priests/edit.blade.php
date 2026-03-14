@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.priests.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Retour
        </a>
        <h1 class="h3 fw-bold mb-0">Modifier le prêtre</h1>
    </div>
    <p class="text-secondary">Mettez à jour les informations, les horaires et les indisponibilités.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form id="priestForm" method="POST" action="{{ route('admin.priests.update', $priest) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Prénom *</label>
                            <input type="text" name="first_name" id="priestFirstName" class="form-control rounded-3 py-2 bg-light" value="{{ old('first_name', $priest->first_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 mt-3 mt-md-0">Nom *</label>
                            <input type="text" name="last_name" id="priestLastName" class="form-control rounded-3 py-2 bg-light" value="{{ old('last_name', $priest->last_name) }}" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Rôle *</label>
                            <select name="role" id="priestRole" class="form-select rounded-3 py-2 bg-light" required>
                                <option value="Curé" {{ $priest->role == 'Curé' ? 'selected' : '' }}>Curé</option>
                                <option value="Vicaire" {{ $priest->role == 'Vicaire' ? 'selected' : '' }}>Vicaire</option>
                                <option value="Aumônier" {{ $priest->role == 'Aumônier' ? 'selected' : '' }}>Aumônier</option>
                                <option value="Curé & Aumônier" {{ $priest->role == 'Curé & Aumônier' ? 'selected' : '' }}>Curé & Aumônier</option>
                                <option value="Vicaire & Aumônier" {{ $priest->role == 'Vicaire & Aumônier' ? 'selected' : '' }}>Vicaire & Aumônier</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 mt-3 mt-md-0">Audience (si aumônier)</label>
                            <select name="audience" id="priestAudience" class="form-select rounded-3 py-2 bg-light">
                                <option value="">Aucune</option>
                                <option value="jeunes" {{ $priest->audience == 'jeunes' ? 'selected' : '' }}>Jeunes</option>
                                <option value="adultes" {{ $priest->audience == 'adultes' ? 'selected' : '' }}>Adultes</option>
                                <option value="enfants" {{ $priest->audience == 'enfants' ? 'selected' : '' }}>Enfants</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4 border rounded-3 p-4 bg-light bg-opacity-50">
                        <h6 class="fw-bold text-primary mb-4"><i class="fa-solid fa-clock me-2"></i>Gestion des Horaires & Indisponibilités</h6>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-600 d-flex justify-content-between align-items-center">
                                    <span>Créneaux disponibles</span>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1" onclick="addTimeSlot()"><i class="fa-solid fa-plus me-1"></i> Ajouter</button>
                                </label>
                                <div id="timeSlotsList" class="mt-3">
                                    <!-- Dynamic time slots injected here by JS -->
                                </div>
                                <div class="form-text mt-2">Saisissez l'heure de début et de fin.</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-600">Dates d'indisponibilité</label>
                                <input type="text" name="unavailable_dates" id="priestUnavailableDates" class="form-control rounded-3 py-2 bg-white" placeholder="Sélectionnez les dates">
                                <div class="form-text mt-2">Les dates sélectionnées seront grisées pour les fidèles.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Photo</label>
                        <input type="file" name="photo" id="priestPhoto" class="form-control rounded-3 py-2 bg-light" accept="image/*">
                        @if($priest->photo_path && file_exists(public_path($priest->photo_path)))
                            <div class="mt-3 p-3 bg-white border rounded-3 d-inline-block">
                                <img src="{{ asset($priest->photo_path) }}" alt="Photo actuelle" class="rounded-3" style="max-height: 80px;">
                                <div class="small fw-bold text-secondary mt-2">Photo actuelle</div>
                            </div>
                        @endif
                        <div class="form-text mt-2">Nouvelle photo ? Laissez vide pour conserver l'actuelle.</div>
                    </div>

                    <div class="mb-5 form-check form-switch p-0">
                        <div class="d-flex align-items-center pt-2">
                            <input class="form-check-input me-3 ms-0 mt-0" type="checkbox" role="switch" name="is_active" value="1" id="priestIsActive" {{ $priest->is_active ? 'checked' : '' }} style="width: 40px; height: 20px;">
                            <label class="form-check-label fw-600 text-dark" for="priestIsActive">
                                Prêtre actif (visible pour les demandes de rendez-vous publiques)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('admin.priests.index') }}" class="btn btn-light rounded-pill px-4">Annuler</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Flatpickr for multi-date selection -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<script>
let fpDates = null;

document.addEventListener("DOMContentLoaded", function() {
    fpDates = flatpickr("#priestUnavailableDates", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        locale: "fr"
    });
    
    // Load existing unavailable dates
    let unavDates = @json($priest->unavailable_dates ?? []);
    if (unavDates.length > 0) {
        fpDates.setDate(unavDates);
    }
    
    // Load existing time slots
    let slots = @json($priest->available_time_slots ?? []);
    if (slots.length > 0) {
        slots.forEach(slot => {
            const parts = slot.split(' - ');
            if (parts.length === 2) {
                addTimeSlot(parts[0], parts[1]);
            }
        });
    } else {
        addTimeSlot();
    }
});

function addTimeSlot(start = '', end = '') {
    const list = document.getElementById('timeSlotsList');
    const div = document.createElement('div');
    div.className = 'd-flex gap-3 mb-3 align-items-center time-slot-item animate-fade-in';
    div.innerHTML = `
        <input type="time" name="time_slot_start[]" class="form-control rounded-3 bg-white" value="${start}" required>
        <span class="text-secondary fw-bold">-</span>
        <input type="time" name="time_slot_end[]" class="form-control rounded-3 bg-white" value="${end}" required>
        <button type="button" class="btn btn-light text-danger rounded-3 p-2" onclick="this.closest('.time-slot-item').remove()" title="Supprimer">
            <i class="fa-solid fa-trash-can"></i>
        </button>
    `;
    list.appendChild(div);
}
</script>

@endsection
