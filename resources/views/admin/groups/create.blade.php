@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <a href="{{ route('admin.participant_groups.index') }}" class="text-secondary text-decoration-none fw-bold mb-3 d-inline-block">
        <i class="fa-solid fa-arrow-left me-2"></i> Retour aux groupes
    </a>
    <h1 class="h3 fw-bold mb-1">Constituer un nouveau groupe</h1>
    <p class="text-secondary">Associez des inscriptions confirmées pour former un groupe précis.</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- Formulaire de Configuration -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-slate-800">Paramètres du groupe</h5>
                <form id="createGroupForm" action="{{ route('admin.participant_groups.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label small fw-bold text-slate-700">Nom du groupe <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="name" name="name" required placeholder="Ex: Groupe Pèlerinage Paris">
                    </div>
                    
                    <div class="mb-4">
                        <label for="target_size" class="form-label small fw-bold text-slate-700">Nombre total de personnes <span class="text-danger">*</span></label>
                        <input type="number" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="target_size" name="target_size" required min="1" placeholder="Ex: 5">
                        <div class="form-text small text-secondary mt-1">Le groupe ne pourra être validé que si la somme des inscriptions sélectionnées correspond exactement à ce nombre.</div>
                    </div>

                    <div class="p-3 bg-blue-50 rounded-3 border border-blue-100 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small fw-bold text-slate-600">Total Sélectionné</span>
                            <span class="h4 fw-black text-blue-600 mb-0" id="current_total">0</span>
                        </div>
                        <div class="progress" style="height: 6px;" id="progress_container">
                            <div class="progress-bar bg-blue-500" id="selection_progress" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="small fw-bold text-danger mt-2 d-none" id="error_message">Dépassement de capacité refusé.</div>
                    </div>

                    <div id="hidden_inputs_container"></div>

                    <button type="submit" id="submit_btn" class="btn btn-primary w-100 rounded-3 py-3 fw-bold shadow-sm" disabled>
                        <i class="fa-solid fa-check me-2"></i> Constituer le groupe
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Liste des Inscriptions -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-slate-800">Inscriptions éligibles</h5>
                <span class="badge bg-slate-100 text-slate-600 rounded-pill px-3 py-2">{{ count($eligibles) }} disponible(s)</span>
            </div>
            <div class="card-body p-0">
                @if(count($eligibles) === 0)
                    <div class="p-5 text-center text-secondary">
                        <i class="fa-solid fa-folder-open mb-3 opacity-50 fs-1"></i>
                        <p class="mb-0">Aucune inscription confirmée libre n'est disponible pour le moment.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                        @foreach($eligibles as $reg)
                            <label class="list-group-item p-4 d-flex align-items-start gap-3 border-bottom hover-bg-slate-50 cursor-pointer js-registration-item" data-uuid="{{ $reg->uuid }}" data-size="{{ $reg->people_count }}">
                                <div class="mt-1">
                                    <input class="form-check-input js-registration-checkbox" type="checkbox" value="{{ $reg->uuid }}" style="width: 1.25rem; height: 1.25rem;">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 text-slate-900">
                                            {{ $reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'Inscription ' . substr($reg->uuid, 0, 8)) }}
                                        </h6>
                                        <span class="badge bg-purple-100 text-purple-700 rounded-pill px-3 py-1 fw-bold">
                                            {{ $reg->people_count }} personne(s)
                                        </span>
                                    </div>
                                    <div class="small text-secondary mb-1">
                                        <span class="me-3"><i class="fa-regular fa-calendar me-1"></i> {{ $reg->registrationActivity->title ?? 'N/A' }}</span>
                                        <span class=""><i class="fa-solid fa-tag me-1"></i> {{ $reg->option }}</span>
                                    </div>
                                    <div class="small text-slate-500 font-monospace">UUID: {{ $reg->uuid }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetSizeInput = document.getElementById('target_size');
    const checkboxes = document.querySelectorAll('.js-registration-checkbox');
    const currentTotalDisplay = document.getElementById('current_total');
    const progressBar = document.getElementById('selection_progress');
    const errorMessage = document.getElementById('error_message');
    const submitBtn = document.getElementById('submit_btn');
    const form = document.getElementById('createGroupForm');
    const hiddenInputsContainer = document.getElementById('hidden_inputs_container');

    let currentTotal = 0;
    let targetSize = 0;

    targetSizeInput.addEventListener('input', function() {
        targetSize = parseInt(this.value) || 0;
        updateUI();
    });

    checkboxes.forEach(chk => {
        chk.addEventListener('change', function(e) {
            const size = parseInt(this.closest('.js-registration-item').dataset.size) || 0;
            
            if (this.checked) {
                // Vérifier si l'ajout dépasse la cible
                if (targetSize > 0 && (currentTotal + size) > targetSize) {
                    this.checked = false; // Annuler la sélection
                    
                    // Feedback visuel erreur
                    errorMessage.classList.remove('d-none');
                    triggerShake(this.closest('.js-registration-item'));
                    setTimeout(() => errorMessage.classList.add('d-none'), 3000);
                    return;
                }
                currentTotal += size;
            } else {
                currentTotal -= size;
            }
            
            errorMessage.classList.add('d-none');
            updateUI();
        });
    });

    function updateUI() {
        // Update text
        currentTotalDisplay.textContent = currentTotal;
        
        // Update progress bar
        if (targetSize > 0) {
            const percentage = Math.min((currentTotal / targetSize) * 100, 100);
            progressBar.style.width = percentage + '%';
            
            if (currentTotal === targetSize) {
                progressBar.classList.remove('bg-blue-500', 'bg-warning');
                progressBar.classList.add('bg-success');
                currentTotalDisplay.classList.remove('text-blue-600');
                currentTotalDisplay.classList.add('text-success');
                submitBtn.disabled = false;
            } else {
                progressBar.classList.remove('bg-success', 'bg-warning');
                progressBar.classList.add('bg-blue-500');
                currentTotalDisplay.classList.remove('text-success');
                currentTotalDisplay.classList.add('text-blue-600');
                submitBtn.disabled = true;
            }
        } else {
            progressBar.style.width = '0%';
            submitBtn.disabled = true;
        }

        // Disable checkboxes that would exceed limit
        checkboxes.forEach(chk => {
            if (!chk.checked) {
                const size = parseInt(chk.closest('.js-registration-item').dataset.size) || 0;
                if (targetSize > 0 && (currentTotal + size) > targetSize) {
                    chk.disabled = true;
                    chk.closest('.js-registration-item').style.opacity = '0.5';
                } else {
                    chk.disabled = false;
                    chk.closest('.js-registration-item').style.opacity = '1';
                }
            }
        });
    }

    form.addEventListener('submit', function(e) {
        hiddenInputsContainer.innerHTML = '';
        checkboxes.forEach(chk => {
            if (chk.checked) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_uuids[]';
                input.value = chk.value;
                hiddenInputsContainer.appendChild(input);
            }
        });
    });

    function triggerShake(element) {
        element.style.transform = 'translate3d(-5px, 0, 0)';
        setTimeout(() => element.style.transform = 'translate3d(5px, 0, 0)', 50);
        setTimeout(() => element.style.transform = 'translate3d(-5px, 0, 0)', 100);
        setTimeout(() => element.style.transform = 'translate3d(5px, 0, 0)', 150);
        setTimeout(() => element.style.transform = 'translate3d(0, 0, 0)', 200);
    }
});
</script>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .border-slate-200 { border-color: #e2e8f0; }
    .text-slate-700 { color: #334155; }
    .text-slate-800 { color: #1e293b; }
    .text-slate-900 { color: #0f172a; }
    .bg-blue-50 { background-color: #eff6ff; }
    .border-blue-100 { border-color: #dbeafe; }
    .text-blue-600 { color: #2563eb; }
    .bg-blue-500 { background-color: #3b82f6; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .text-slate-600 { color: #475569; }
    .bg-purple-100 { background-color: #f3e8ff; }
    .text-purple-700 { color: #7e22ce; }
    .hover-bg-slate-50:hover { background-color: #f8fafc; }
    .cursor-pointer { cursor: pointer; }
    
    .js-registration-item { transition: all 0.2s ease; }
</style>
@endsection
