@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('admin.priests.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-3">
            <i class="fa-solid fa-arrow-left me-2"></i> Retour
        </a>
        <h1 class="h3 fw-bold mb-0">Ajouter un prêtre</h1>
    </div>
    <p class="text-secondary">Renseignez les informations du prêtre pour qu'il soit disponible aux rendez-vous.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-4 p-md-5">
                <form id="priestForm" method="POST" action="{{ route('admin.priests.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Prénom *</label>
                            <input type="text" name="first_name" id="priestFirstName" class="form-control rounded-3 py-2 bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 mt-3 mt-md-0">Nom *</label>
                            <input type="text" name="last_name" id="priestLastName" class="form-control rounded-3 py-2 bg-light" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-600">Rôle *</label>
                            <select name="role" id="priestRole" class="form-select rounded-3 py-2 bg-light" required>
                                <option value="Curé">Curé</option>
                                <option value="Vicaire">Vicaire</option>
                                <option value="Aumônier">Aumônier</option>
                                <option value="Curé & Aumônier">Curé & Aumônier</option>
                                <option value="Vicaire & Aumônier">Vicaire & Aumônier</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 mt-3 mt-md-0">Audience (si aumônier)</label>
                            <select name="audience" id="priestAudience" class="form-select rounded-3 py-2 bg-light">
                                <option value="">Aucune</option>
                                <option value="jeunes">Jeunes</option>
                                <option value="adultes">Adultes</option>
                                <option value="enfants">Enfants</option>
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
                                    <!-- Dynamic time slots injected here -->
                                </div>
                                <div class="form-text mt-2">Saisissez l'heure de début et de fin (ex: 08:00 - 09:00).</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-600">Dates d'indisponibilité</label>
                                <input type="text" name="unavailable_dates" id="priestUnavailableDates" class="form-control rounded-3 py-2 bg-white" placeholder="Sélectionnez les dates">
                                <div class="form-text mt-2">Ces dates seront grisées sur le calendrier du site public.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Photo</label>
                        <input type="file" name="photo" id="priestPhoto" class="form-control rounded-3 py-2 bg-light" accept="image/*">
                    </div>

                    <div class="mb-5 form-check form-switch p-0">
                        <div class="d-flex align-items-center pt-2">
                            <input class="form-check-input me-3 ms-0 mt-0" type="checkbox" role="switch" name="is_active" value="1" id="priestIsActive" checked style="width: 40px; height: 20px;">
                            <label class="form-check-label fw-600 text-dark" for="priestIsActive">
                                Prêtre actif (visible pour les demandes de rendez-vous publiques)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 border-top pt-4">
                        <a href="{{ route('admin.priests.index') }}" class="btn btn-light rounded-pill px-4">Annuler</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold">Ajouter le prêtre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mt-4 mt-lg-0">
        <div class="card border-0 shadow-sm bg-primary-light" style="border-radius: 20px;">
            <div class="card-body p-4 text-primary">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-circle-info me-2"></i> Informations</h5>
                <p class="small mb-3">La gestion des horaires par un père lui permet de recevoir des requêtes claires aux heures qui l'arrangent.</p>
                <ul class="small mb-0 ps-3">
                    <li class="mb-2"><strong>Photo</strong> : Format carré recommandé de bonne qualité.</li>
                    <li class="mb-2"><strong>Indisponibilité</strong> : Cliquez sur les jours où le prêtre sera en retraite ou indisponible.</li>
                    <li><strong>Statut</strong> : Si désactivé, le profil disparaîtra du site.</li>
                </ul>
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
    
    // Default time slot for new entries
    addTimeSlot();
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

<style>
    .bg-primary-light { background-color: #f0f4ff !important; }
</style>
@endsection
