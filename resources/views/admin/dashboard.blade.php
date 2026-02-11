@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Tableau de bord</h1>
    <p class="text-secondary">Bienvenue sur votre espace d'administration "Saint Michel".</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5 animate-fade-in" style="animation-delay: 0.1s">
    <div class="col-md-3">
        <div class="glass-card h-100">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-primary-light p-3 me-3">
                    <i class="fa-solid fa-calendar-check text-primary fs-4"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-600">Total Réservations</div>
                    <div class="h3 fw-bold mb-0">{{ $stats['total_reservations'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card h-100">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-warning p-3 me-3 bg-opacity-10">
                    <i class="fa-solid fa-hourglass-half text-warning fs-4"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-600">En attente</div>
                    <div class="h3 fw-bold mb-0 text-warning">{{ $stats['pending_reservations'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card h-100">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-success p-3 me-3 bg-opacity-10">
                    <i class="fa-solid fa-calendar-day text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-600">Réservations Aujourd'hui</div>
                    <div class="h3 fw-bold mb-0 text-success">{{ $stats['today_reservations'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card h-100">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-info p-3 me-3 bg-opacity-10">
                    <i class="fa-solid fa-home text-info fs-4"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-600">Salles Disponibles</div>
                    <div class="h3 fw-bold mb-0 text-info">{{ $stats['available_rooms'] }}/{{ $stats['total_rooms'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Content -->
<div class="row g-4 animate-fade-in" style="animation-delay: 0.2s">
    <div class="col-md-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Dernières réservations</h5>
                <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Tout voir</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-secondary">
                        <tr>
                            <th>Client</th>
                            <th>Salle</th>
                            <th>Date & Heure</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_reservations as $res)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-weight: 600;">
                                        {{ strtoupper(substr($res->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-600">{{ $res->first_name }} {{ $res->last_name }}</div>
                                        <div class="small text-secondary">{{ $res->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $res->room->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="fw-600">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d/m/Y') }}</div>
                                <div class="small text-secondary"><i class="fa-regular fa-clock me-1"></i> {{ $res->time_slot }}</div>
                            </td>
                            <td>
                                @if($res->status == 'pending')
                                    <span class="badge-pill bg-warning bg-opacity-10 text-warning">En attente</span>
                                @elseif($res->status == 'validated')
                                    <span class="badge-pill bg-success bg-opacity-10 text-success">Validée</span>
                                @else
                                    <span class="badge-pill bg-danger bg-opacity-10 text-danger">Annulée</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-secondary">Aucune réservation récente.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card">
            <h5 class="fw-bold mb-4">Actions Rapides</h5>
            <div class="d-grid gap-3">
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-primary text-start p-3 rounded-4 d-flex align-items-center">
                    <i class="fa-solid fa-plus-circle fs-4 me-3"></i>
                    <div>
                        <div class="fw-bold">Ajouter une salle</div>
                        <div class="small opacity-75">Configurer une nouvelle salle</div>
                    </div>
                </a>
                <a href="{{ route('time_slots.index') }}" class="btn btn-outline-info text-dark text-start p-3 rounded-4 d-flex align-items-center">
                    <i class="fa-solid fa-clock-rotate-left fs-4 me-3 text-info"></i>
                    <div>
                        <div class="fw-bold">Gérer les créneaux</div>
                        <div class="small opacity-75">Modifier les horaires dispos</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
