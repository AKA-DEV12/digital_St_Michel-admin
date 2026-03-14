@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in d-flex justify-content-between align-items-end flex-wrap gap-3">
    <div>
        <h1 class="h3 fw-bold mb-1">Rendez-vous avec les Pères</h1>
        <p class="text-secondary mb-0">Gérez les demandes de rencontres pastorales.</p>
    </div>
    <div>
        <a href="{{ route('admin.priest_appointments.export', request()->all()) }}" class="btn btn-outline-success rounded-3 px-4 py-2 shadow-sm fw-600">
            <i class="fa-solid fa-file-csv me-2"></i> Exporter CSV
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4 animate-fade-in bg-white">
    <div class="card-body p-4">
        <form action="{{ route('admin.priest_appointments.index') }}" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-secondary"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Nom, email, téléphone, objet..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Date de début</label>
                <input type="date" name="date_from" class="form-control bg-light border-0" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">Date de fin</label>
                <input type="date" name="date_to" class="form-control bg-light border-0" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold">Filtrer</button>
            </div>
        </form>
    </div>
</div>

<x-data-table :headers="['Client', 'Père & Objet', 'Date & Horaire', 'Statut', 'Actions']" :collection="$appointments">
    <x-slot name="title">Liste des rendez-vous</x-slot>
    
    <x-slot name="actions">
        <div class="btn-group glass-card mb-0 p-1 d-flex gap-1 border-0 bg-gray-50" style="border-radius: 12px;">
            <a href="{{ route('admin.priest_appointments.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $status == 'pending' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                En attente
            </a>
            <a href="{{ route('admin.priest_appointments.index', ['status' => 'validated']) }}" class="btn btn-xs {{ $status == 'validated' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                Validés
            </a>
            <a href="{{ route('admin.priest_appointments.index', ['status' => 'cancelled']) }}" class="btn btn-xs {{ $status == 'cancelled' ? 'btn-danger bg-danger text-white' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                Annulés
            </a>
        </div>
    </x-slot>

    @forelse($appointments as $appointment)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                    {{ strtoupper(substr($appointment->full_name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $appointment->full_name }}</div>
                    <div class="small text-secondary">{{ $appointment->email }}</div>
                    <div class="small text-secondary"><i class="fa-solid fa-phone me-1" style="font-size: 10px;"></i>{{ $appointment->phone }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">Père {{ $appointment->priest->first_name }} {{ $appointment->priest->last_name }}</div>
            <div class="text-xs text-secondary mt-1 text-truncate" style="max-width: 200px;">
                {{ $appointment->object }}
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark fw-600">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
            <div class="text-xs text-primary mt-1 bg-primary-light rounded-pill px-2 py-1 d-inline-block fw-bold">
                <i class="fa-regular fa-clock me-1"></i> {{ $appointment->time_slot }}
            </div>
        </td>
        <td class="px-6 py-4">
            @if($appointment->status == 'pending')
                <span class="badge rounded-pill bg-warning text-white px-3 py-1 border-0 shadow-sm fw-bold">En attente</span>
            @elseif($appointment->status == 'validated')
                <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold">Validé</span>
            @else
                <span class="badge rounded-pill bg-danger text-white px-3 py-1 border-0 shadow-sm fw-bold">Annulé</span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li><a class="dropdown-item small py-2 rounded-2" href="javascript:void(0)" onclick="showAppointmentDetails({{ $appointment->id }}, {{ json_encode($appointment) }}, {{ json_encode($appointment->priest) }})"><i class="fa-solid fa-eye me-2 opacity-100 text-primary"></i> <b>Détails</b></a></li>
                    
                    @if($appointment->status == 'pending')
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form action="{{ route('admin.priest_appointments.validate', $appointment) }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item small py-2 rounded-2 text-success fw-bold bg-success-subtle mb-1" onclick="return confirm('Valider ce rendez-vous ? Un email sera envoyé au demandeur.')">
                                    <i class="fa-solid fa-circle-check me-2"></i> VALIDER
                                </button>
                            </form>
                        </li>
                    @endif
                    @if($appointment->status != 'cancelled')
                        <li>
                            <form action="{{ route('admin.priest_appointments.cancel', $appointment) }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold bg-danger-subtle" onclick="return confirm('Annuler ce rendez-vous ? Un email sera envoyé au demandeur.')">
                                    <i class="fa-solid fa-circle-xmark me-2"></i> ANNULER
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center py-5 text-secondary">
            <i class="fa-solid fa-inbox fs-2 mb-3 text-light"></i>
            <p class="mb-0">Aucun rendez-vous trouvé pour ce statut ou cette recherche.</p>
        </td>
    </tr>
    @endforelse
</x-data-table>

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

<!-- Appointment Details Modal -->
<div class="modal fade animate-fade-in" id="appointmentDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    Détails du Rendez-vous <span id="modalID" class="text-secondary small fw-normal">#0</span>
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
                                <p class="text-secondary small mb-0 fw-bold"><i class="fa-solid fa-phone me-1 mt-1"></i><span id="modalPhone"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="modal-detail-item bg-white border">
                            <div class="modal-detail-label"><i class="fa-solid fa-comment-dots me-1 small"></i> Objet de la rencontre</div>
                            <div class="modal-detail-value fs-6" id="modalObject">...</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="modal-detail-item">
                            <div class="modal-detail-label"><i class="fa-solid fa-cross me-1 small"></i> Avec le Père</div>
                            <div class="modal-detail-value" id="modalPriest">...</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-detail-item border border-primary bg-primary-light">
                            <div class="modal-detail-label text-primary"><i class="fa-solid fa-calendar-day me-1 small"></i> Date & Horaire retenus</div>
                            <div class="modal-detail-value text-primary fs-5" id="modalDateTime">...</div>
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
function showAppointmentDetails(id, appointment, priest) {
    document.getElementById('modalID').textContent = '#' + id;
    document.getElementById('modalName').textContent = appointment.full_name;
    document.getElementById('modalEmail').textContent = appointment.email;
    document.getElementById('modalPhone').textContent = appointment.phone;
    
    // Replace newlines with <br> for object display
    document.getElementById('modalObject').innerHTML = appointment.object.replace(/\n/g, '<br>');
    
    document.getElementById('modalPriest').textContent = priest.first_name + ' ' + priest.last_name + ' (' + priest.role + ')';
    
    const date = new Date(appointment.appointment_date);
    const dateStr = date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
    document.getElementById('modalDateTime').innerHTML = '<strong>' + dateStr + '</strong> à ' + appointment.time_slot;
    
    let statusHTML = '';
    if (appointment.status === 'pending') {
        statusHTML = '<span class="badge rounded-pill bg-warning text-white px-3 py-2 fw-bold">EN ATTENTE</span>';
    } else if (appointment.status === 'validated') {
        statusHTML = '<span class="badge rounded-pill bg-success text-white px-3 py-2 fw-bold">VALIDÉ</span>';
    } else {
        statusHTML = '<span class="badge rounded-pill bg-danger text-white px-3 py-2 fw-bold">ANNULÉ</span>';
    }
    document.getElementById('modalStatusBadge').innerHTML = statusHTML;
    
    const modal = new bootstrap.Modal(document.getElementById('appointmentDetailsModal'));
    modal.show();
}
</script>
@endsection
