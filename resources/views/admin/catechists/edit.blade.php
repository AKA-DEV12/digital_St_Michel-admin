@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <a href="{{ route('admin.catechists.index') }}" class="text-secondary text-decoration-none fw-bold mb-3 d-inline-block">
        <i class="fa-solid fa-arrow-left me-2"></i> Retour aux catéchistes
    </a>
    <h1 class="h3 fw-bold mb-1">Modifier le catéchiste</h1>
    <p class="text-secondary">Mettez à jour les informations de {{ $catechist->nom_prenom }}.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <form action="{{ route('admin.catechists.update', $catechist->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informations principales -->
                    <h5 class="fw-bold mb-4 text-slate-800">Informations principales</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="nom_prenom" class="form-label small fw-bold text-slate-700">Nom et Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="nom_prenom" name="nom_prenom" required value="{{ old('nom_prenom', $catechist->nom_prenom) }}" placeholder="Ex: Jean Dupont">
                            @error('nom_prenom')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="date_naissance" class="form-label small fw-bold text-slate-700">Date de naissance</label>
                            <input type="date" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $catechist->date_naissance?->format('Y-m-d')) }}">
                            @error('date_naissance')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label small fw-bold text-slate-700">Photo</label>
                            <div class="d-flex gap-3 align-items-start">
                                <div id="photo-preview-container" class="rounded-4 border-slate-200 bg-slate-50 d-flex align-items-center justify-content-center overflow-hidden" style="width: 100px; height: 100px; border: 2px dashed #e2e8f0;">
                                    @if($catechist->photo)
                                        <img id="photo-preview" src="{{ $catechist->photo_url }}" alt="Preview" class="w-100 h-100 object-fit-cover">
                                        <i class="fa-solid fa-user text-slate-300 fs-2 d-none" id="photo-placeholder"></i>
                                    @else
                                        <i class="fa-solid fa-user text-slate-300 fs-2" id="photo-placeholder"></i>
                                        <img id="photo-preview" src="#" alt="Preview" class="w-100 h-100 object-fit-cover d-none">
                                    @endif
                                </div>
                                <div class="grow">
                                    <div class="mb-2">
                                        <input type="file" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="photo" name="photo" accept="image/*" onchange="previewFile()">
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="border-top grow"></div>
                                        <span class="small text-secondary fw-bold">OU</span>
                                        <div class="border-top grow"></div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary rounded-3 w-100 py-2 fw-bold" onclick="startWebcam()">
                                        <i class="fa-solid fa-camera me-2"></i> Prendre une photo
                                    </button>
                                    <input type="hidden" name="captured_image" id="captured_image">
                                </div>
                            </div>
                            @error('photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="lieu_habitation" class="form-label small fw-bold text-slate-700">Lieu d'habitation</label>
                            <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="lieu_habitation" name="lieu_habitation" value="{{ old('lieu_habitation', $catechist->lieu_habitation) }}" placeholder="Ex: Abidjan, Cocody">
                            @error('lieu_habitation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="situation_matrimoniale" class="form-label small fw-bold text-slate-700">Situation matrimoniale</label>
                            <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="situation_matrimoniale" name="situation_matrimoniale">
                                <option value="">Sélectionner...</option>
                                <option value="Celibataire" {{ old('situation_matrimoniale', $catechist->situation_matrimoniale) === 'Celibataire' ? 'selected' : '' }}>Célibataire</option>
                                <option value="Concubinage" {{ old('situation_matrimoniale', $catechist->situation_matrimoniale) === 'Concubinage' ? 'selected' : '' }}>Concubinage</option>
                                <option value="Marier" {{ old('situation_matrimoniale', $catechist->situation_matrimoniale) === 'Marier' ? 'selected' : '' }}>Marié(e)</option>
                                <option value="Divorcer" {{ old('situation_matrimoniale', $catechist->situation_matrimoniale) === 'Divorcer' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option value="Veuve / Veuf" {{ old('situation_matrimoniale', $catechist->situation_matrimoniale) === 'Veuve / Veuf' ? 'selected' : '' }}>Veuve / Veuf</option>
                            </select>
                            @error('situation_matrimoniale')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="nombre_enfant" class="form-label small fw-bold text-slate-700">Nombre d'enfants</label>
                            <input type="number" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="nombre_enfant" name="nombre_enfant" min="0" value="{{ old('nombre_enfant', $catechist->nombre_enfant) }}" placeholder="0">
                            @error('nombre_enfant')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="situation_professionnelle" class="form-label small fw-bold text-slate-700">Situation professionnelle</label>
                        <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="situation_professionnelle" name="situation_professionnelle" value="{{ old('situation_professionnelle', $catechist->situation_professionnelle) }}" placeholder="Ex: Enseignant">
                        @error('situation_professionnelle')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Antécédents catéchèse -->
                    <h5 class="fw-bold mb-4 text-slate-800">Antécédents en catéchèse</h5>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="antecedent" name="antecedent" {{ old('antecedent', $catechist->antecedent) ? 'checked' : '' }} onchange="toggleAntecedentFields()">
                            <label class="form-check-label fw-bold text-slate-700" for="antecedent">
                                A déjà fait la catéchèse auparavant
                            </label>
                        </div>
                    </div>

                    <div id="antecedent_fields" style="display: {{ old('antecedent', $catechist->antecedent) ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="antecedent_date" class="form-label small fw-bold text-slate-700">Date</label>
                                <input type="date" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="antecedent_date" name="antecedent_date" value="{{ old('antecedent_date', $catechist->antecedent_date?->format('Y-m-d')) }}">
                                @error('antecedent_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="antecedent_annee_catechese" class="form-label small fw-bold text-slate-700">Année de catéchèse</label>
                                <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="antecedent_annee_catechese" name="antecedent_annee_catechese">
                                    <option value="">Sélectionner...</option>
                                    <option value="1ere" {{ old('antecedent_annee_catechese', $catechist->antecedent_annee_catechese) === '1ere' ? 'selected' : '' }}>1ère Année</option>
                                    <option value="2eme" {{ old('antecedent_annee_catechese', $catechist->antecedent_annee_catechese) === '2eme' ? 'selected' : '' }}>2ème Année</option>
                                    <option value="3eme" {{ old('antecedent_annee_catechese', $catechist->antecedent_annee_catechese) === '3eme' ? 'selected' : '' }}>3ème Année</option>
                                    <option value="4eme" {{ old('antecedent_annee_catechese', $catechist->antecedent_annee_catechese) === '4eme' ? 'selected' : '' }}>4ème Année</option>
                                    <option value="5eme" {{ old('antecedent_annee_catechese', $catechist->antecedent_annee_catechese) === '5eme' ? 'selected' : '' }}>5ème Année</option>
                                </select>
                                @error('antecedent_annee_catechese')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="antecedent_paroisse" class="form-label small fw-bold text-slate-700">Paroisse</label>
                                <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="antecedent_paroisse" name="antecedent_paroisse" value="{{ old('antecedent_paroisse', $catechist->antecedent_paroisse) }}" placeholder="Ex: Saint Michel">
                                @error('antecedent_paroisse')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Appartenance groupe/mouvement -->
                    <h5 class="fw-bold mb-4 text-slate-800">Appartenance à un groupe/mouvement</h5>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="groupe_mouvement" name="groupe_mouvement" {{ old('groupe_mouvement', $catechist->groupe_mouvement) ? 'checked' : '' }} onchange="toggleGroupeFields()">
                            <label class="form-check-label fw-bold text-slate-700" for="groupe_mouvement">
                                Appartient à un groupe/mouvement
                            </label>
                        </div>
                    </div>

                    <div id="groupe_fields" style="display: {{ old('groupe_mouvement', $catechist->groupe_mouvement) ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="group_id" class="form-label small fw-bold text-slate-700">Groupe</label>
                                <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="group_id" name="group_id" onchange="loadMembers()">
                                    <option value="">Sélectionner un groupe...</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" {{ old('group_id', $catechist->group_id) == $group->id ? 'selected' : '' }}>{{ $group->nom_groupe }}</option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="member_id" class="form-label small fw-bold text-slate-700">Membre</label>
                                <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="member_id" name="member_id" {{ !old('groupe_mouvement', $catechist->groupe_mouvement) ? 'disabled' : '' }}>
                                    <option value="">Sélectionner d'abord un groupe...</option>
                                    @if($catechist->member_id)
                                        <option value="{{ $catechist->member_id }}" selected>{{ $catechist->member->nom }} {{ $catechist->member->prenom }}</option>
                                    @endif
                                </select>
                                @error('member_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations baptême -->
                    <h5 class="fw-bold mb-4 text-slate-800">Informations sur le baptême</h5>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="baptiser" name="baptiser" {{ old('baptiser', $catechist->baptiser) ? 'checked' : '' }} onchange="toggleBaptemeFields()">
                            <label class="form-check-label fw-bold text-slate-700" for="baptiser">
                                Est baptisé(e)
                            </label>
                        </div>
                    </div>

                    <div id="bapteme_fields" style="display: {{ old('baptiser', $catechist->baptiser) ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="date_bapteme" class="form-label small fw-bold text-slate-700">Date du baptême</label>
                                <input type="date" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="date_bapteme" name="date_bapteme" value="{{ old('date_bapteme', $catechist->date_bapteme?->format('Y-m-d')) }}">
                                @error('date_bapteme')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="paroisse_bapteme" class="form-label small fw-bold text-slate-700">Paroisse du baptême</label>
                                <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="paroisse_bapteme" name="paroisse_bapteme" value="{{ old('paroisse_bapteme', $catechist->paroisse_bapteme) }}" placeholder="Ex: Saint Michel">
                                @error('paroisse_bapteme')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="carnet_bapteme" class="form-label small fw-bold text-slate-700">Numéro carnet</label>
                                <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="carnet_bapteme" name="carnet_bapteme" value="{{ old('carnet_bapteme', $catechist->carnet_bapteme) }}" placeholder="Ex: CB2023001">
                                @error('carnet_bapteme')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations catéchèse actuelle -->
                    <h5 class="fw-bold mb-4 text-slate-800">Catéchèse actuelle</h5>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="annee_catechese" class="form-label small fw-bold text-slate-700">Année de catéchèse <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="annee_catechese" name="annee_catechese" required>
                                <option value="">Sélectionner...</option>
                                <option value="Fin de Catéchèse" {{ old('annee_catechese', $catechist->annee_catechese) === 'Fin de Catéchèse' ? 'selected' : '' }}>Fin de Catéchèse</option>
                                <option value="5eme" {{ old('annee_catechese', $catechist->annee_catechese) === '5eme' ? 'selected' : '' }}>5ème année</option>
                                <option value="4eme" {{ old('annee_catechese', $catechist->annee_catechese) === '4eme' ? 'selected' : '' }}>4ème année</option>
                                <option value="3eme" {{ old('annee_catechese', $catechist->annee_catechese) === '3eme' ? 'selected' : '' }}>3ème année</option>
                                <option value="2eme" {{ old('annee_catechese', $catechist->annee_catechese) === '2eme' ? 'selected' : '' }}>2ème année</option>
                                <option value="1ere" {{ old('annee_catechese', $catechist->annee_catechese) === '1ere' ? 'selected' : '' }}>1ère année</option>
                            </select>
                            @error('annee_catechese')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="statut_catechese" class="form-label small fw-bold text-slate-700">Statut <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="statut_catechese" name="statut_catechese" required>
                                <option value="">Sélectionner...</option>
                                <option value="En cours" {{ old('statut_catechese', $catechist->statut_catechese) === 'En cours' ? 'selected' : '' }}>En cours</option>
                                <option value="Terminee" {{ old('statut_catechese', $catechist->statut_catechese) === 'Terminee' ? 'selected' : '' }}>Terminée</option>
                            </select>
                            @error('statut_catechese')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.catechists.index') }}" class="btn btn-light rounded-3 px-4 py-2 fw-bold">
                            <i class="fa-solid fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-save me-2"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Webcam Modal -->
<div class="modal fade" id="webcamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-slate-900 text-white border-0">
                <h5 class="modal-title fw-bold">Prendre une photo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="stopWebcam()"></button>
            </div>
            <div class="modal-body p-0 bg-black overflow-hidden" style="height: 400px;">
                <video id="webcam-video" autoplay playsinline class="w-100 h-100 object-fit-cover"></video>
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
let stream = null;

function previewFile() {
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const file = document.getElementById('photo').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        preview.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
        document.getElementById('captured_image').value = '';
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

async function startWebcam() {
    const modalElement = document.getElementById('webcamModal');
    const modal = new bootstrap.Modal(modalElement);
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
    if (placeholder) placeholder.classList.add('d-none');
    capturedInput.value = dataUrl;
    
    // Clear file input
    document.getElementById('photo').value = '';
    
    stopWebcam();
    bootstrap.Modal.getInstance(document.getElementById('webcamModal')).hide();
}

function toggleAntecedentFields() {
    const fields = document.getElementById('antecedent_fields');
    const checkbox = document.getElementById('antecedent');
    fields.style.display = checkbox.checked ? 'block' : 'none';
    
    if (!checkbox.checked) {
        document.getElementById('antecedent_date').value = '';
        document.getElementById('antecedent_annee_catechese').value = '';
        document.getElementById('antecedent_paroisse').value = '';
    }
}

function toggleGroupeFields() {
    const fields = document.getElementById('groupe_fields');
    const checkbox = document.getElementById('groupe_mouvement');
    fields.style.display = checkbox.checked ? 'block' : 'none';
    
    if (!checkbox.checked) {
        document.getElementById('group_id').value = '';
        document.getElementById('member_id').value = '';
        document.getElementById('member_id').disabled = true;
    }
}

}

let stream = null;

function previewFile() {
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('photo-placeholder');
    const file = document.getElementById('photo').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        preview.classList.remove('d-none');
        if (placeholder) placeholder.classList.add('d-none');
        document.getElementById('captured_image').value = '';
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

async function startWebcam() {
    const modalElement = document.getElementById('webcamModal');
    const modal = new bootstrap.Modal(modalElement);
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
    if (placeholder) placeholder.classList.add('d-none');
    capturedInput.value = dataUrl;
    
    // Clear file input
    document.getElementById('photo').value = '';
    
    stopWebcam();
    bootstrap.Modal.getInstance(document.getElementById('webcamModal')).hide();
}

function toggleBaptemeFields() {
    const fields = document.getElementById('bapteme_fields');
    const checkbox = document.getElementById('baptiser');
    fields.style.display = checkbox.checked ? 'block' : 'none';
    
    if (!checkbox.checked) {
        document.getElementById('date_bapteme').value = '';
        document.getElementById('paroisse_bapteme').value = '';
        document.getElementById('carnet_bapteme').value = '';
    }
}

function loadMembers() {
    const groupId = document.getElementById('group_id').value;
    const memberSelect = document.getElementById('member_id');
    
    if (groupId) {
        fetch(`/catechese/groups/${groupId}/members`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                memberSelect.innerHTML = '<option value="">Sélectionner un membre...</option>';
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                if (data.length === 0) {
                    memberSelect.innerHTML += '<option value="">Aucun membre</option>';
                } else {
                    data.forEach(member => {
                        memberSelect.innerHTML += `<option value="${member.id}">${member.nom} ${member.prenom}</option>`;
                    });
                }
                memberSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                memberSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                memberSelect.disabled = true;
            });
    } else {
        memberSelect.innerHTML = '<option value="">Sélectionner d\'abord un groupe...</option>';
        memberSelect.disabled = true;
    }
}

// Initialiser l'affichage des champs au chargement
document.addEventListener('DOMContentLoaded', function() {
    toggleAntecedentFields();
    toggleGroupeFields();
    toggleBaptemeFields();
});
</script>
@endsection
