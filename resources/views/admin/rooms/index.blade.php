@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Gestion des Salles</h1>
    <p class="text-secondary">Configurez et gérez les salles disponibles pour les réservations.</p>
</div>

<x-data-table :headers="['Icon', 'Nom & Description', 'Capacité', 'Tarifs', 'Statut', 'Actions']" :collection="$rooms">
    <x-slot name="title">Liste des salles</x-slot>
    
    <x-slot name="actions">
        <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 text-white fw-600 shadow-sm" data-bs-toggle="modal" data-bs-target="#roomModal">
            <i class="fa-solid fa-plus me-2"></i> Nouveau
        </button>
        <button class="btn btn-sm btn-success rounded-3 px-3 py-2 text-white fw-600 shadow-sm" data-bs-toggle="modal" data-bs-target="#paymentConfigModal">
            <i class="fa-solid fa-money-bill-transfer me-2"></i> Configurer le paiement
        </button>
    </x-slot>

    @foreach($rooms as $room)
    <tr>
        <td class="px-6 py-4">
            <div class="rounded-3 bg-primary-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                <i class="fa-solid fa-{{ $room->icon ?? 'door-open' }}"></i>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $room->name }}</div>
            <div class="text-xs text-secondary mt-1 text-truncate" style="max-width: 250px;">{{ $room->description }}</div>
        </td>
        <td class="px-6 py-4">
            <span class="badge rounded-pill bg-light text-dark border-0 px-3 py-2 fw-600">
                <i class="fa-solid fa-user-group me-1 opacity-50"></i> {{ $room->capacity }} pers.
            </span>
        </td>
        <td class="px-6 py-4">
            <div class="d-flex flex-column gap-1">
                @if($room->price_per_hour)
                    <span class="small text-muted">H: {{ number_format($room->price_per_hour, 0, ',', ' ') }} FCFA</span>
                @endif
                @if($room->price_half_day)
                    <span class="small text-muted">½J: {{ number_format($room->price_half_day, 0, ',', ' ') }} FCFA</span>
                @endif
                @if($room->price_full_day)
                    <span class="small text-muted">J: {{ number_format($room->price_full_day, 0, ',', ' ') }} FCFA</span>
                @endif
                @if(!$room->price_per_hour && !$room->price_half_day && !$room->price_full_day)
                    <span class="small text-muted">Non défini</span>
                @endif
            </div>
        </td>
        <td class="px-6 py-4">
            @if($room->status == 'disponible')
                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1 border-0">Disponible</span>
            @else
                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1 border-0">Indisponible</span>
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
                                onclick="editRoom({{ json_encode($room) }})"
                                data-bs-toggle="modal" data-bs-target="#roomModal">
                            <i class="fa-solid fa-pen-to-square me-2"></i> MODIFIER
                        </button>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Supprimer cette salle ?')">
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

<!-- Room Modal (Unchanged functional part) -->
<div class="modal fade" id="roomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <form id="roomForm" method="POST" action="{{ route('rooms.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="modal-header border-0 p-4">
                    <h5 class="fw-bold mb-0" id="modalTitle">Ajouter une salle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Nom de la salle</label>
                        <input type="text" name="name" id="roomName" class="form-control rounded-3 py-2" placeholder="Ex: Salle Saint-Michel" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600 small">Capacité</label>
                            <input type="number" name="capacity" id="roomCapacity" class="form-control rounded-3 py-2" placeholder="Ex: 50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 small">Icône (FontAwesome)</label>
                            <input type="text" name="icon" id="roomIcon" class="form-control rounded-3 py-2" placeholder="Ex: home">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Statut</label>
                        <select name="status" id="roomStatus" class="form-select rounded-3 py-2" required>
                            <option value="disponible">Disponible</option>
                            <option value="indisponible">Indisponible</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Description</label>
                        <textarea name="description" id="roomDescription" class="form-control rounded-3" rows="3" placeholder="Description de la salle..."></textarea>
                    </div>
                    <div class="border-top pt-3">
                        <h6 class="fw-600 mb-3 text-primary">
                            <i class="fa-solid fa-coins me-2"></i>Tarification
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-600 small">Prix/heure (FCFA)</label>
                                <input type="number" name="price_per_hour" id="pricePerHour" class="form-control rounded-3 py-2" placeholder="0" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600 small">Prix demi-journée (FCFA)</label>
                                <input type="number" name="price_half_day" id="priceHalfDay" class="form-control rounded-3 py-2" placeholder="0" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600 small">Prix journée (FCFA)</label>
                                <input type="number" name="price_full_day" id="priceFullDay" class="form-control rounded-3 py-2" placeholder="0" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="alert alert-info small mt-3 rounded-3">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>Règles de tarification:</strong><br>
                            • Horaire: minimum 2 heures (calcul automatique)<br>
                            • Demi-journée: 4+ heures<br>
                            • Journée: 8+ heures
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Configuration Modal -->
<div class="modal fade" id="paymentConfigModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 p-4">
                <h5 class="fw-bold mb-0">
                    <i class="fa-solid fa-money-bill-transfer me-2 text-success"></i>
                    Configuration des moyens de paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentConfigForm" method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 pt-0">
                    <div class="alert alert-info small rounded-3 mb-4">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        <strong>Configuration globale:</strong> Ces informations seront utilisées pour toutes les réservations de salles.
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-600">Lien de paiement Wave</label>
                            <input type="text" name="settings[wave_number]" id="global_wave_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="https://wave.com/..." value="{{ $paymentSettings['wave_number'] ?? '' }}">
                            <div class="form-text small opacity-75">Lien direct vers la page de paiement Wave</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-600">Numéro MTN MoMo</label>
                            <input type="text" name="settings[mtn_number]" id="global_mtn_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00" value="{{ $paymentSettings['mtn_number'] ?? '' }}">
                            <div class="form-text small opacity-75">Numéro pour les dépôts MTN</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-600">Numéro ORANGE Money</label>
                            <input type="text" name="settings[orange_number]" id="global_orange_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00" value="{{ $paymentSettings['orange_number'] ?? '' }}">
                            <div class="form-text small opacity-75">Numéro pour les dépôts Orange</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-600">Numéro MOOV Money</label>
                            <input type="text" name="settings[moov_number]" id="global_moov_number" class="form-control rounded-3 border-0 shadow-sm" placeholder="00 00 00 00 00" value="{{ $paymentSettings['moov_number'] ?? '' }}">
                            <div class="form-text small opacity-75">Numéro pour les dépôts Moov</div>
                        </div>
                    </div>
                    
                    <div class="border-top pt-3 mt-4">
                        <h6 class="fw-600 mb-3 text-primary">
                            <i class="fa-solid fa-bell me-2"></i> Notifications
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-slate-600">Email de notification des paiements</label>
                                <input type="email" name="settings[payment_notification_email]" id="payment_notification_email" class="form-control rounded-3 border-0 shadow-sm" placeholder="admin@example.com" value="{{ $paymentSettings['payment_notification_email'] ?? '' }}">
                                <div class="form-text small opacity-75">Email pour recevoir les notifications de nouvelles réservations avec paiement</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">
                        <i class="fa-solid fa-save me-2"></i> Enregistrer la configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editRoom(room) {
    const form = document.getElementById('roomForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');
    form.action = `/rooms/${room.id}`;
    methodInput.value = 'PUT';
    modalTitle.innerText = 'Modifier la salle';
    document.getElementById('roomName').value = room.name;
    document.getElementById('roomCapacity').value = room.capacity;
    document.getElementById('roomIcon').value = room.icon;
    document.getElementById('roomStatus').value = room.status;
    document.getElementById('roomDescription').value = room.description;
    document.getElementById('pricePerHour').value = room.price_per_hour || '';
    document.getElementById('priceHalfDay').value = room.price_half_day || '';
    document.getElementById('priceFullDay').value = room.price_full_day || '';
}
document.getElementById('roomModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('roomForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');
    form.action = "{{ route('rooms.store') }}";
    methodInput.value = 'POST';
    modalTitle.innerText = 'Ajouter une salle';
    form.reset();
});
</script>

<style>
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .text-success { color: #16a34a !important; }
    .text-danger { color: #dc2626 !important; }
</style>
@endsection
