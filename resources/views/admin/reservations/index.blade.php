@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Réservations</h1>
    <p class="text-secondary">Gérez les demandes de réservation des salles.</p>
</div>

<x-data-table :headers="['Nom', 'Objet & Salle', 'Date', 'Statut', 'Actions']" :collection="$reservations">
    <x-slot name="title">Liste des réservations</x-slot>
    
    <x-slot name="actions">
        <div class="btn-group glass-card mb-0 p-1 d-flex gap-1 border-0 bg-gray-50" style="border-radius: 12px;">
            <a href="{{ route('reservations.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $status == 'pending' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                En attente
            </a>
            <a href="{{ route('reservations.index', ['status' => 'validated']) }}" class="btn btn-xs {{ $status == 'validated' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                Validées
            </a>
        </div>
    </x-slot>

    @foreach($reservations as $reservation)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                    {{ strtoupper(substr($reservation->first_name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $reservation->first_name }} {{ $reservation->last_name }}</div>
                    <div class="small text-secondary">{{ $reservation->email }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $reservation->reservation_object }}</div>
            <div class="text-xs text-secondary mt-1">
                <i class="fa-solid fa-building me-1 opacity-50"></i> {{ $reservation->room->name ?? 'N/A' }}
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark">{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</div>
            <div class="text-xs text-secondary mt-1">
                <i class="fa-regular fa-clock me-1 opacity-50"></i> {{ $reservation->time_slot }}
            </div>
        </td>
        <td class="px-6 py-4">
            @if($reservation->status == 'pending')
                <span class="badge rounded-pill bg-warning text-white px-3 py-1 border-0 shadow-sm fw-bold">En attente</span>
            @elseif($reservation->status == 'validated')
                <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold">Validée</span>
            @else
                <span class="badge rounded-pill bg-danger text-white px-3 py-1 border-0 shadow-sm fw-bold">Annulée</span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li><a class="dropdown-item small py-2 rounded-2" href="javascript:void(0)" onclick="showReservationDetails({{ json_encode($reservation) }})"><i class="fa-solid fa-eye me-2 opacity-100 text-primary"></i> <b>Détails</b></a></li>
                    
                    @if($status == 'pending')
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form action="{{ route('reservations.validate', $reservation) }}" method="POST" id="validate-{{ $reservation->id }}">
                                @csrf
                                <button type="submit" class="dropdown-item small py-2 rounded-2 text-success fw-bold bg-success-subtle mb-1">
                                    <i class="fa-solid fa-circle-check me-2"></i> VALIDER
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" id="cancel-{{ $reservation->id }}">
                                @csrf
                                <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold bg-danger-subtle">
                                    <i class="fa-solid fa-circle-xmark me-2"></i> ANNULER
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
    @endforeach
</x-data-table>
@endsection

<style>
    .bg-warning-subtle { background-color: #fffbeb !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .text-warning { color: #d97706 !important; }
    .text-success { color: #16a34a !important; }
    .text-danger { color: #dc2626 !important; }

    .modal-detail-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        height: 100%;
    }
    .modal-detail-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .modal-detail-value {
        color: #1e293b;
        font-weight: 600;
    }
</style>

<!-- Reservation Details Modal -->
<div class="modal fade animate-fade-in" id="reservationDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    Détails de la Réservation <span id="modalID" class="text-secondary small fw-normal">#0</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Client Information -->
                    <div class="col-12">
                        <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-10 rounded-4 d-flex align-items-center mb-2">
                            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; color: var(--primary);">
                                <i class="fa-solid fa-user fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0" id="modalName">Chargement...</h6>
                                <p class="text-secondary small mb-0" id="modalEmail">...</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="modal-detail-item">
                            <div class="modal-detail-label"><i class="fa-solid fa-phone me-1 small"></i> Téléphone</div>
                            <div class="modal-detail-value" id="modalPhone">...</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-detail-item">
                            <div class="modal-detail-label"><i class="fa-solid fa-users me-1 small"></i> Groupe / Mouvement</div>
                            <div class="modal-detail-value" id="modalGroup">...</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="modal-detail-item bg-white border">
                            <div class="modal-detail-label"><i class="fa-solid fa-comment-dots me-1 small"></i> Objet de la réservation</div>
                            <div class="modal-detail-value fs-5" id="modalObject">...</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="modal-detail-item">
                            <div class="modal-detail-label"><i class="fa-solid fa-door-open me-1 small"></i> Salle réservée</div>
                            <div class="modal-detail-value" id="modalRoom">...</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-detail-item">
                            <div class="modal-detail-label"><i class="fa-solid fa-calendar-day me-1 small"></i> Date & Horaire</div>
                            <div class="modal-detail-value" id="modalDateTime">...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 justify-content-between">
                <div id="modalStatusBadge"></div>
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script>
function showReservationDetails(reservation) {
    // Populate simple fields
    document.getElementById('modalID').textContent = '#' + reservation.id;
    document.getElementById('modalName').textContent = reservation.first_name + ' ' + reservation.last_name;
    document.getElementById('modalEmail').textContent = reservation.email;
    document.getElementById('modalPhone').textContent = reservation.phone || 'Non renseigné';
    document.getElementById('modalGroup').textContent = reservation.group_name || reservation.other_group_name || 'Aucun';
    document.getElementById('modalObject').textContent = reservation.reservation_object;
    document.getElementById('modalRoom').textContent = reservation.room ? reservation.room.name : 'N/A';
    
    // Format date
    const date = new Date(reservation.reservation_date);
    const dateStr = date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
    document.getElementById('modalDateTime').textContent = dateStr + ' | ' + reservation.time_slot;
    
    // Status Badge
    let statusHTML = '';
    if (reservation.status === 'pending') {
        statusHTML = '<span class="badge rounded-pill bg-warning text-white px-3 py-2 fw-bold">EN ATTENTE</span>';
    } else if (reservation.status === 'validated') {
        statusHTML = '<span class="badge rounded-pill bg-success text-white px-3 py-2 fw-bold">VALIDÉE</span>';
    } else {
        statusHTML = '<span class="badge rounded-pill bg-danger text-white px-3 py-2 fw-bold">ANNULÉE</span>';
    }
    document.getElementById('modalStatusBadge').innerHTML = statusHTML;
    
    // Show Modal
    const modal = new bootstrap.Modal(document.getElementById('reservationDetailsModal'));
    modal.show();
}
</script>
