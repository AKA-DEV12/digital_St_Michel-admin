@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <h1 class="h3 fw-bold mb-1">Activités et Inscriptions</h1>
    <p class="text-secondary">Gérez les pèlerinages et autres activités disponibles pour inscription.</p>
</div>

<x-data-table :headers="['Activité', 'Date & Heure', 'Lieu', 'Statut', 'Action']" :collection="$activities">
    <x-slot name="title">Liste des activités</x-slot>
    
    <x-slot name="actions">
        <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#activityModal">
            <i class="fa-solid fa-plus me-2"></i> Nouvelle Activité
        </button>
    </x-slot>

    @foreach($activities as $activity)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-{{ $activity->color }}-100 text-{{ $activity->color }}-600 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-person-walking fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $activity->title }}</div>
                    <div class="small text-secondary">{{ $activity->subtitle }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark fw-600">{{ \Carbon\Carbon::parse($activity->date)->format('d/m/Y') }}</div>
            <div class="small text-secondary">
                <i class="fa-regular fa-clock me-1 opacity-50"></i> {{ $activity->start_time ? substr($activity->start_time, 0, 5) : '--:--' }} - {{ $activity->end_time ? substr($activity->end_time, 0, 5) : '--:--' }}
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-secondary small">
                <i class="fa-solid fa-location-dot me-1 text-danger opacity-50"></i> {{ $activity->location ?? 'Non défini' }}
            </div>
        </td>
        <td class="px-6 py-4">
            @if($activity->is_active)
                <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold">Actif</span>
            @else
                <span class="badge rounded-pill bg-gray-400 text-white px-3 py-1 border-0 shadow-sm fw-bold">Inactif</span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li>
                        <button class="dropdown-item small py-2 rounded-2 text-primary fw-bold mb-1" 
                                onclick="editActivity({{ json_encode($activity) }})"
                                data-bs-toggle="modal" data-bs-target="#activityModal">
                            <i class="fa-solid fa-pen-to-square me-2"></i> MODIFIER
                        </button>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Supprimer cette activité ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold bg-danger-subtle">
                                <i class="fa-solid fa-trash me-2"></i> SUPPRIMER
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @endforeach
</x-data-table>

<!-- Activity Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-slate-900 text-white border-0 py-4 px-6">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Activité</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="activityForm" method="POST" action="{{ route('activities.store') }}">
                @csrf
                <div id="method-field"></div>
                <div class="modal-body p-6">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-slate-700">Titre de l'activité</label>
                            <input type="text" name="title" id="title" class="form-control rounded-3 py-2" required placeholder="Ex: PÈLERINAGE DES JEUNES 2026">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-slate-700">Sous-titre / Courte description</label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control rounded-3 py-2" placeholder="Ex: Un moment fort de foi et de fraternité">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-slate-700">Date</label>
                            <input type="date" name="date" id="date" class="form-control rounded-3 py-2" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-slate-700">Heure de début</label>
                            <input type="time" name="start_time" id="start_time" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-slate-700">Heure de fin</label>
                            <input type="time" name="end_time" id="end_time" class="form-control rounded-3 py-2">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-700">Lieu</label>
                            <input type="text" name="location" id="location" class="form-control rounded-3 py-2" placeholder="Ex: Sanctuaire Marial d'Arigbo">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-700">Montant de l'inscription (FCFA)</label>
                            <input type="number" name="registration_amount" id="registration_amount" class="form-control rounded-3 py-2" placeholder="Ex: 5000">
                        </div>
                        
                        <!-- Payment Numbers Group -->
                        <div class="col-md-12">
                            <div class="p-4 bg-slate-50 rounded-4 border border-slate-100">
                                <h6 class="fw-bold text-slate-900 mb-3"><i class="fa-solid fa-money-bill-transfer me-2 text-crimson-600"></i> Numéros de paiement (Optionnels)</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-slate-600">Numéro WAVE</label>
                                        <input type="text" name="wave_number" id="wave_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-slate-600">Numéro MTN</label>
                                        <input type="text" name="mtn_number" id="mtn_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-slate-600">Numéro ORANGE</label>
                                        <input type="text" name="orange_number" id="orange_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold text-slate-600">Numéro MOOV</label>
                                        <input type="text" name="moov_number" id="moov_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-700">Couleur du thème</label>
                            <select name="color" id="color" class="form-select rounded-3 py-2">
                                <option value="blue">Bleu</option>
                                <option value="indigo">Indigo</option>
                                <option value="slate">Gris</option>
                                <option value="purple">Violet</option>
                                <option value="emerald">Emeraude</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch bg-slate-50 p-3 rounded-3">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="is_active" value="1" checked>
                                <label class="form-check-label fw-bold text-slate-800" for="is_active">Rendre l'activité active (visible par le public)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-0 p-4">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#activityModal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold shadow-lg shadow-blue-200">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editActivity(activity) {
    const form = document.getElementById('activityForm');
    form.action = `/activities/${activity.id}`;
    document.getElementById('modalTitle').innerText = 'Modifier l\'activité';
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('title').value = activity.title;
    document.getElementById('subtitle').value = activity.subtitle;
    document.getElementById('date').value = activity.date;
    document.getElementById('start_time').value = activity.start_time;
    document.getElementById('end_time').value = activity.end_time;
    document.getElementById('location').value = activity.location || '';
    document.getElementById('registration_amount').value = activity.registration_amount || '';
    document.getElementById('wave_number').value = activity.wave_number || '';
    document.getElementById('mtn_number').value = activity.mtn_number || '';
    document.getElementById('orange_number').value = activity.orange_number || '';
    document.getElementById('moov_number').value = activity.moov_number || '';
    document.getElementById('color').value = activity.color;
    document.getElementById('is_active').checked = !!activity.is_active;
}

// Reset modal on close or "New Activity" click
document.getElementById('activityModal').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || event.relatedTarget.getAttribute('data-bs-target') === '#activityModal' && !event.relatedTarget.onclick) {
        const form = document.getElementById('activityForm');
        form.action = "{{ route('activities.store') }}";
        document.getElementById('modalTitle').innerText = 'Nouvelle Activité';
        document.getElementById('method-field').innerHTML = '';
        form.reset();
    }
});
</script>

<style>
    .bg-blue-100 { background-color: #dbeafe; }
    .text-blue-600 { color: #2563eb; }
    .bg-indigo-100 { background-color: #e0e7ff; }
    .text-indigo-600 { color: #4f46e5; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .text-slate-600 { color: #475569; }
    .bg-purple-100 { background-color: #f3e8ff; }
    .text-purple-600 { color: #9333ea; }
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-600 { color: #059669; }
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-900 { background-color: #0f172a; }
</style>
@endsection
