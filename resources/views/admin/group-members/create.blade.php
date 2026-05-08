@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Ajouter un membre</h1>
            <p class="text-secondary">Enregistrez un nouveau membre dans un groupe paroissial.</p>
        </div>
        <a href="{{ route('group-members.index') }}" class="btn btn-light rounded-3 px-4">
            <i class="fa-solid fa-arrow-left me-2"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
            <div class="p-6">
                <form action="{{ route('group-members.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ request('group_id') }}">
                    
                    <div class="mb-4 bg-light p-4 rounded-3 border animate-fade-in">
                        <label class="form-label fw-bold text-dark">Ce membre appartient-il déjà à un autre groupe ?</label>
                        <div class="d-flex gap-4 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_existing" id="is_existing_no" value="no" checked onchange="toggleExistingMember()">
                                <label class="form-check-label" for="is_existing_no">Non, c'est un nouveau membre</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_existing" id="is_existing_yes" value="yes" onchange="toggleExistingMember()">
                                <label class="form-check-label" for="is_existing_yes">Oui, il est déjà dans un groupe</label>
                            </div>
                        </div>

                        <div id="existing_member_section" class="mt-4 pt-3 border-top" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-600 small text-secondary">Groupe d'origine</label>
                                    <select id="source_group_id" class="form-select rounded-3 py-2" onchange="loadMembers(this.value)">
                                        <option value="">Sélectionner un groupe...</option>
                                        @foreach($groups ?? [] as $group)
                                            <option value="{{ $group->id }}">{{ $group->nom_groupe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-600 small text-secondary">Sélectionner le membre *</label>
                                    <select name="existing_member_id" id="existing_member_id" class="form-select rounded-3 py-2">
                                        <option value="">Sélectionnez d'abord un groupe...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="alert alert-info mt-2 mb-0 py-2 small">
                                <i class="fa-solid fa-info-circle me-1"></i> Les informations personnelles du membre seront automatiquement récupérées.
                            </div>
                        </div>
                    </div>
                    
                    <div id="new_member_section" class="row animate-fade-in">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Nom & Prénom *</label>
                            <input type="text" name="nom_prenom" class="form-control rounded-3 py-2" 
                                   value="{{ old('nom_prenom') }}" required
                                   placeholder="ex: Jean Dupont">
                            @error('nom_prenom')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Date de Naissance</label>
                            <input type="date" name="date_naissance" class="form-control rounded-3 py-2" 
                                   value="{{ old('date_naissance') }}">
                            @error('date_naissance')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Situation Matrimoniale *</label>
                            <select name="situation_matrimoniale" class="form-select rounded-3 py-2" required>
                                <option value="">Sélectionner...</option>
                                <option value="Celibataire" {{ old('situation_matrimoniale') == 'Celibataire' ? 'selected' : '' }}>Célibataire</option>
                                <option value="Concubinage" {{ old('situation_matrimoniale') == 'Concubinage' ? 'selected' : '' }}>Concubinage</option>
                                <option value="Marier" {{ old('situation_matrimoniale') == 'Marier' ? 'selected' : '' }}>Marié(e)</option>
                                <option value="Divorcer" {{ old('situation_matrimoniale') == 'Divorcer' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option value="Veuve / Veuf" {{ old('situation_matrimoniale') == 'Veuve / Veuf' ? 'selected' : '' }}>Veuve/Veuf</option>
                            </select>
                            @error('situation_matrimoniale')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Situation Professionnelle</label>
                            <input type="text" name="situation_professionnelle" class="form-control rounded-3 py-2" 
                                   value="{{ old('situation_professionnelle') }}"
                                   placeholder="ex: Enseignant, Commerçant...">
                            @error('situation_professionnelle')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfant" class="form-control rounded-3 py-2" 
                                   value="{{ old('nombre_enfant', 0) }}" min="0">
                            @error('nombre_enfant')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-600 small text-secondary">Photo</label>
                            <div class="d-flex gap-3 align-items-start">
                                <div class="flex-shrink-0">
                                    <div id="photo-preview-container" class="rounded-4 border-slate-200 bg-light d-flex align-items-center justify-content-center overflow-hidden" style="width: 120px; height: 120px; border: 2px dashed #e2e8f0;">
                                        <i class="fa-solid fa-user text-secondary fs-1" id="photo-placeholder"></i>
                                        <img id="photo-preview" src="#" alt="Preview" class="w-100 h-100 object-fit-cover d-none" style="object-fit: cover;">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="mb-2">
                                        <input type="file" id="photo" name="photo" class="form-control rounded-3 py-2" 
                                               accept="image/*" onchange="previewFile()">
                                        <div class="text-secondary small mt-1">
                                            Formats acceptés: JPEG, PNG, JPG (max 2MB)
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="border-top flex-grow-1"></div>
                                        <span class="small text-secondary fw-bold">OU</span>
                                        <div class="border-top flex-grow-1"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary rounded-3 w-100 py-2 fw-bold" onclick="startWebcam()">
                                        <i class="fa-solid fa-camera me-2"></i> Prendre une photo en direct
                                    </button>
                                    <input type="hidden" name="captured_image" id="captured_image">
                                    @error('photo')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div> <!-- End new_member_section -->

                    <hr class="my-4 text-muted">
                    <h5 class="fw-bold mb-3 text-dark">Informations d'adhésion au groupe</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Date d'adhésion au nouveau groupe *</label>
                            <input type="date" name="date_adhesion" class="form-control rounded-3 py-2" 
                                   value="{{ old('date_adhesion') }}" required>
                            @error('date_adhesion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-600 small text-secondary">Responsabilité dans ce groupe</label>
                            <input type="text" name="responsabilite" class="form-control rounded-3 py-2" 
                                   value="{{ old('responsabilite') }}"
                                   placeholder="ex: Trésorier, Secrétaire...">
                            @error('responsabilite')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                        <a href="{{ route('group-members.index') }}" class="btn btn-light rounded-3 px-4">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">
                            <i class="fa-solid fa-save me-2"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
            <div class="p-4 bg-light border-bottom">
                <h5 class="fw-bold mb-0">
                    <i class="fa-solid fa-info-circle text-primary me-2"></i>
                    Informations
                </h5>
            </div>
            <div class="p-4">
                <div class="mb-3">
                    <h6 class="fw-600 text-dark mb-2">Photo du membre</h6>
                    <p class="text-secondary small mb-0">
                        Vous pouvez télécharger une photo ou prendre une photo avec l'appareil photo de votre appareil.
                    </p>
                </div>
                <div class="mb-3">
                    <h6 class="fw-600 text-dark mb-2">Date d'adhésion</h6>
                    <p class="text-secondary small mb-0">
                        Correspond à la date d'entrée officielle du membre dans le groupe.
                    </p>
                </div>
                <div class="mb-0">
                    <h6 class="fw-600 text-dark mb-2">Responsabilité</h6>
                    <p class="text-secondary small mb-0">
                        Optionnel - Indiquez si le membre a une responsabilité particulière dans le groupe.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Webcam Modal -->
<div class="modal fade" id="webcamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">Prendre une photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="stopWebcam()"></button>
            </div>
            <div class="modal-body p-0 bg-black overflow-hidden" style="height: 400px;">
                <video id="webcam-video" autoplay playsinline class="w-100 h-100" style="object-fit: cover;"></video>
                <canvas id="webcam-canvas" class="d-none"></canvas>
            </div>
            <div class="modal-footer border-0 bg-white justify-content-center py-3">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal" onclick="stopWebcam()">Annuler</button>
                <button type="button" class="btn btn-primary rounded-pill px-5 fw-bold" onclick="capturePhoto()">
                    <i class="fa-solid fa-camera me-2"></i> Capturer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Données des groupes et membres injectées par le contrôleur
const groupsData = @json($groups ?? []);

let stream = null;

function previewFile() {
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const file = document.getElementById('photo').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        preview.classList.remove('d-none');
        placeholder.classList.add('d-none');
        document.getElementById('captured_image').value = '';
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.classList.add('d-none');
        placeholder.classList.remove('d-none');
    }
}

async function startWebcam() {
    const modal = new bootstrap.Modal(document.getElementById('webcamModal'));
    const video = document.getElementById('webcam-video');
    
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
        video.srcObject = stream;
        modal.show();
    } catch (err) {
        alert("Impossible d'accéder à la caméra : " + err.message);
    }
}

function stopWebcam() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
}

function capturePhoto() {
    const video = document.getElementById('webcam-video');
    const canvas = document.getElementById('webcam-canvas');
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const capturedInput = document.getElementById('captured_image');
    
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    
    const dataUrl = canvas.toDataURL('image/png');
    preview.src = dataUrl;
    preview.classList.remove('d-none');
    placeholder.classList.add('d-none');
    capturedInput.value = dataUrl;
    
    // Clear file input
    document.getElementById('photo').value = '';
    
    stopWebcam();
    bootstrap.Modal.getInstance(document.getElementById('webcamModal')).hide();
}

function toggleExistingMember() {
    const isExisting = document.getElementById('is_existing_yes').checked;
    const existingSection = document.getElementById('existing_member_section');
    const newMemberSection = document.getElementById('new_member_section');
    
    // Récupérer les champs requis pour les activer/désactiver
    const requiredInputs = newMemberSection.querySelectorAll('input[required], select[required]');
    
    if (isExisting) {
        existingSection.style.display = 'block';
        newMemberSection.style.display = 'none';
        
        // Désactiver les attributs required pour permettre la soumission
        requiredInputs.forEach(input => input.removeAttribute('required'));
    } else {
        existingSection.style.display = 'none';
        newMemberSection.style.display = 'flex'; // row uses flex
        
        // Réactiver les attributs required
        requiredInputs.forEach(input => input.setAttribute('required', 'required'));
        // Clear select
        document.getElementById('existing_member_id').value = '';
    }
}

function loadMembers(groupId) {
    const memberSelect = document.getElementById('existing_member_id');
    memberSelect.innerHTML = '<option value="">Sélectionner le membre...</option>';
    
    if (!groupId) return;
    
    const group = groupsData.find(g => g.id == groupId);
    if (group && group.members && group.members.length > 0) {
        group.members.forEach(member => {
            const option = document.createElement('option');
            option.value = member.id;
            option.textContent = member.nom_prenom + (member.responsabilite ? ' (' + member.responsabilite + ')' : '');
            memberSelect.appendChild(option);
        });
    } else {
        memberSelect.innerHTML = '<option value="">Aucun membre dans ce groupe</option>';
    }
}
</script>
@endsection
