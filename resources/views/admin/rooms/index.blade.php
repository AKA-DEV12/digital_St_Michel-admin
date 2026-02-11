@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Gestion des Salles</h1>
    <p class="text-secondary">Configurez et gérez les salles disponibles pour les réservations.</p>
</div>

<x-data-table :headers="['Icon', 'Nom & Description', 'Capacité', 'Statut', 'Actions']" :collection="$rooms">
    <x-slot name="title">Liste des salles</x-slot>
    
    <x-slot name="actions">
        <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 text-white fw-600 shadow-sm" data-bs-toggle="modal" data-bs-target="#roomModal">
            <i class="fa-solid fa-plus me-2"></i> Nouveau
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
                    <div class="mb-0">
                        <label class="form-label fw-600 small">Description</label>
                        <textarea name="description" id="roomDescription" class="form-control rounded-3" rows="3" placeholder="Description de la salle..."></textarea>
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
