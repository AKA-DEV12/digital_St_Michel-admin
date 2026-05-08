@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <a href="{{ route('admin.catechists.index') }}" class="text-secondary text-decoration-none fw-bold mb-3 d-inline-block">
        <i class="fa-solid fa-arrow-left me-2"></i> Retour aux catéchistes
    </a>
    <h1 class="h3 fw-bold mb-1">Ajouter un nouveau catéchiste</h1>
    <p class="text-secondary">Enregistrez un nouveau catéchiste dans la base de données paroissiale.</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <h5 class="fw-bold mb-4 text-slate-800">Informations du catéchiste</h5>
                <form action="{{ route('admin.catechists.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="nom_prenom" class="form-label small fw-bold text-slate-700">Nom et Prénom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="nom_prenom" name="nom_prenom" required placeholder="Ex: Jean Dupont">
                        @error('nom_prenom')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date_naissance" class="form-label small fw-bold text-slate-700">Date de naissance</label>
                        <input type="date" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="date_naissance" name="date_naissance">
                        @error('date_naissance')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-slate-700">Photo du catéchiste</label>
                        <div class="d-flex gap-3 align-items-start">
                            <div id="photo-preview-container" class="rounded-4 border-slate-200 bg-slate-50 d-flex align-items-center justify-content-center overflow-hidden" style="width: 120px; height: 120px; border: 2px dashed #e2e8f0;">
                                <i class="fa-solid fa-user text-slate-300 fs-1" id="photo-placeholder"></i>
                                <img id="photo-preview" src="#" alt="Preview" class="w-100 h-100 object-fit-cover d-none">
                            </div>
                            <div class="grow">
                                <div class="mb-2">
                                    <input type="file" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="photo" name="photo" accept="image/*" onchange="previewFile()">
                                    <div class="form-text small">Télécharger un fichier existant (JPG, PNG)</div>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="border-top grow"></div>
                                    <span class="small text-secondary fw-bold">OU</span>
                                    <div class="border-top grow"></div>
                                </div>
                                <button type="button" class="btn btn-outline-primary rounded-3 w-100 py-2 fw-bold" onclick="startWebcam()">
                                    <i class="fa-solid fa-camera me-2"></i> Prendre une photo en direct
                                </button>
                                <input type="hidden" name="captured_image" id="captured_image">
                            </div>
                        </div>
                        @error('photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="lieu_habitation" class="form-label small fw-bold text-slate-700">Lieu d'habitation</label>
                        <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="lieu_habitation" name="lieu_habitation" placeholder="Ex: Abidjan, Cocody">
                        @error('lieu_habitation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="situation_matrimoniale" class="form-label small fw-bold text-slate-700">Situation matrimoniale</label>
                        <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="situation_matrimoniale" name="situation_matrimoniale">
                            <option value="">Sélectionner...</option>
                            <option value="Celibataire">Célibataire</option>
                            <option value="Concubinage">Concubinage</option>
                            <option value="Marier">Marié(e)</option>
                            <option value="Divorcer">Divorcé(e)</option>
                            <option value="Veuve / Veuf">Veuve / Veuf</option>
                        </select>
                        @error('situation_matrimoniale')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nombre_enfant" class="form-label small fw-bold text-slate-700">Nombre d'enfants</label>
                        <input type="number" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="nombre_enfant" name="nombre_enfant" min="0" placeholder="0">
                        @error('nombre_enfant')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="situation_professionnelle" class="form-label small fw-bold text-slate-700">Situation professionnelle / Fonction</label>
                        <input type="text" class="form-control rounded-3 py-2 bg-slate-50 border-slate-200" id="situation_professionnelle" name="situation_professionnelle" placeholder="Ex: Enseignant">
                        @error('situation_professionnelle')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Antécédents catéchèse -->
                    <div class="border rounded-4 p-4 mb-4 bg-light shadow-sm">
                        <div class="d-flex align-items-center mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="antecedent" name="antecedent" onchange="toggleAntecedentFields()">
                                <label class="form-check-label fw-bold text-slate-700 ms-2" for="antecedent">
                                    <i class="fa-solid fa-history text-info me-2"></i>
                                    Antécédents en catéchèse
                                </label>
                            </div>
                        </div>

                        <div id="antecedent_fields" style="display: none;" class="animate-fade-in">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="antecedent_annee_catechese" class="form-label small fw-bold text-slate-700">Année de catéchèse</label>
                                    <select class="form-select rounded-3 py-2 bg-white border-slate-300" id="antecedent_annee_catechese" name="antecedent_annee_catechese">
                                        <option value="">Sélectionner...</option>
                                        <option value="1ere">1ère Année</option>
                                        <option value="2eme">2ème Année</option>
                                        <option value="3eme">3ème Année</option>
                                        <option value="4eme">4ème Année</option>
                                        <option value="5eme">5ème Année</option>
                                    </select>
                                    @error('antecedent_annee_catechese')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="antecedent_paroisse" class="form-label small fw-bold text-slate-700">Paroisse</label>
                                    <input type="text" class="form-control rounded-3 py-2 bg-white border-slate-300" id="antecedent_paroisse" name="antecedent_paroisse" placeholder="Ex: Saint Michel">
                                    @error('antecedent_paroisse')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="antecedent_date" class="form-label small fw-bold text-slate-700">Date</label>
                                    <input type="date" class="form-control rounded-3 py-2 bg-white border-slate-300" id="antecedent_date" name="antecedent_date">
                                    @error('antecedent_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appartenance groupe/mouvement -->
                    <div class="border rounded-3 p-4 mb-4 bg-light">
                        <div class="d-flex align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="groupe_mouvement" name="groupe_mouvement" onchange="toggleGroupeFields()">
                                <label class="form-check-label fw-bold text-slate-700" for="groupe_mouvement">
                                    <i class="fa-solid fa-users text-warning me-2"></i>
                                    Appartenance à un groupe/mouvement
                                </label>
                            </div>
                        </div>

                        <div id="groupe_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="group_id" class="form-label small fw-bold text-slate-700">Groupe</label>
                                    <select class="form-select rounded-3 py-2 bg-white border-slate-300" id="group_id" name="group_id" onchange="loadMembers()">
                                        <option value="">Sélectionner un groupe...</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->nom_groupe }}</option>
                                        @endforeach
                                    </select>
                                    @error('group_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="member_id" class="form-label small fw-bold text-slate-700">Membre</label>
                                    <select class="form-select rounded-3 py-2 bg-white border-slate-300" id="member_id" name="member_id" disabled>
                                        <option value="">Sélectionner d'abord un groupe...</option>
                                    </select>
                                    @error('member_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations baptême -->
                    <div class="border rounded-3 p-4 mb-4 bg-light">
                        <div class="d-flex align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="baptiser" name="baptiser" onchange="toggleBaptemeFields()">
                                <label class="form-check-label fw-bold text-slate-700" for="baptiser">
                                    <i class="fa-solid fa-cross text-primary me-2"></i>
                                    Informations sur le baptême
                                </label>
                            </div>
                        </div>

                        <div id="bapteme_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="date_bapteme" class="form-label small fw-bold text-slate-700">Date du baptême</label>
                                    <input type="date" class="form-control rounded-3 py-2 bg-white border-slate-300" id="date_bapteme" name="date_bapteme">
                                    @error('date_bapteme')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="paroisse_bapteme" class="form-label small fw-bold text-slate-700">Paroisse du baptême</label>
                                    <input type="text" class="form-control rounded-3 py-2 bg-white border-slate-300" id="paroisse_bapteme" name="paroisse_bapteme" placeholder="Ex: Saint Michel">
                                    @error('paroisse_bapteme')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="carnet_bapteme" class="form-label small fw-bold text-slate-700">Numéro carnet</label>
                                    <input type="text" class="form-control rounded-3 py-2 bg-white border-slate-300" id="carnet_bapteme" name="carnet_bapteme" placeholder="Ex: CB2023001">
                                    @error('carnet_bapteme')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations catéchèse actuelle -->
                    <h6 class="fw-bold mb-3 text-slate-800">
                        <i class="fa-solid fa-graduation-cap text-primary me-2"></i>
                        Catéchèse actuelle
                    </h6>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="annee_catechese" class="form-label small fw-bold text-slate-700">Année de catéchèse en cours <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="annee_catechese" name="annee_catechese" required>
                                <option value="">Sélectionner...</option>
                                <option value="Fin de Catéchèse">Fin de Catéchèse</option>
                                <option value="5eme">5ème Année</option>
                                <option value="4eme">4ème Année</option>
                                <option value="3eme">3ème Année</option>
                                <option value="2eme">2ème Année</option>
                                <option value="1ere">1ère Année</option>
                            </select>
                            @error('annee_catechese')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="statut_catechese" class="form-label small fw-bold text-slate-700">Statut <span class="text-danger">*</span></label>
                            <select class="form-select rounded-3 py-2 bg-slate-50 border-slate-200" id="statut_catechese" name="statut_catechese" required>
                                <option value="En cours">En cours</option>
                                <option value="Terminee">Terminée</option>
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
                            <i class="fa-solid fa-plus me-2"></i> Ajouter le catéchiste
                        </button>
                    </div>

                    </form>
            </div>
        </div>
    </div>
    
    <!-- Bloc d'informations à droite -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-slate-800">
                    <i class="fa-solid fa-info-circle text-primary me-2"></i>Guide d'utilisation
                </h6>
                <div class="small text-muted">
                    <p class="mb-3">Ce formulaire vous permet d'ajouter un nouveau catéchiste dans la base de données paroissiale.</p>
                    
                    <div class="mb-3">
                        <strong class="text-slate-700">Champs obligatoires :</strong>
                        <ul class="list-unstyled mt-2">
                            <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i>Nom et Prénom</li>
                            <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i>Année de catéchèse</li>
                            <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i>Statut</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <strong class="text-slate-700">Champs optionnels :</strong>
                        <ul class="list-unstyled mt-2">
                            <li class="mb-1"><i class="fa-solid fa-circle text-muted me-2" style="font-size: 8px;"></i>Date de naissance</li>
                            <li class="mb-1"><i class="fa-solid fa-circle text-muted me-2" style="font-size: 8px;"></i>Photo</li>
                            <li class="mb-1"><i class="fa-solid fa-circle text-muted me-2" style="font-size: 8px;"></i>Informations personnelles</li>
                            <li class="mb-1"><i class="fa-solid fa-circle text-muted me-2" style="font-size: 8px;"></i>Antécédents catéchèse</li>
                            <li class="mb-1"><i class="fa-solid fa-circle text-muted me-2" style="font-size: 8px;"></i>Appartenance groupe</li>
                            <li class="mb-1"><i class="fa-solid fa-circle text-muted me-2" style="font-size: 8px;"></i>Informations baptême</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-sm alert-info rounded-3">
                        <i class="fa-solid fa-lightbulb me-1"></i>
                        <strong>Conseil :</strong> Le matricule sera généré automatiquement.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-slate-800">
                    <i class="fa-solid fa-question-circle text-primary me-2"></i>Aide rapide
                </h6>
                <div class="small text-muted">
                    <div class="mb-3">
                        <strong class="text-slate-700">Antécédents catéchèse :</strong>
                        <p class="mb-0">Cochez cette option si le catéchiste a déjà suivi une formation catéchétique précédemment.</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong class="text-slate-700">Appartenance groupe :</strong>
                        <p class="mb-0">Lie le catéchiste à un groupe/mouvement existant dans la paroisse.</p>
                    </div>
                    
                    <div class="mb-3">
                        <strong class="text-slate-700">Photo :</strong>
                        <p class="mb-0">Formats acceptés : JPG, PNG, GIF (taille maximale : 2MB)</p>
                    </div>
                    
                    <div class="d-flex align-items-center text-primary">
                        <i class="fa-solid fa-phone me-2"></i>
                        <small>Contactez l'administrateur pour toute question</small>
                    </div>
                </div>
            </div>
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

function toggleAntecedentFields() {
    const fields = document.getElementById('antecedent_fields');
    const checkbox = document.getElementById('antecedent');
    fields.style.display = checkbox.checked ? 'block' : 'none';
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

function toggleBaptemeFields() {
    const fields = document.getElementById('bapteme_fields');
    const checkbox = document.getElementById('baptiser');
    fields.style.display = checkbox.checked ? 'block' : 'none';
}

function loadMembers() {
    const groupId = document.getElementById('group_id').value;
    const memberSelect = document.getElementById('member_id');
    
    if (groupId) {
        memberSelect.innerHTML = '<option value="">Chargement...</option>';
        memberSelect.disabled = true;
        
        fetch(`/catechese/groups/${groupId}/members`)
            .then(response => response.json())
            .then(data => {
                memberSelect.innerHTML = '<option value="">Sélectionner un membre...</option>';
                data.forEach(member => {
                    memberSelect.innerHTML += `<option value="${member.id}">${member.nom} ${member.prenom}</option>`;
                });
                memberSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                memberSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    } else {
        memberSelect.innerHTML = '<option value="">Sélectionner d\'abord un groupe...</option>';
        memberSelect.disabled = true;
    }
}
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .object-fit-cover { object-fit: cover; }
</style>
@endsection
