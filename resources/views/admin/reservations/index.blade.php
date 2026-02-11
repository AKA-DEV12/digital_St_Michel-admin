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
                    <li><a class="dropdown-item small py-2 rounded-2" href="#"><i class="fa-solid fa-eye me-2 opacity-100 text-primary"></i> <b>Détails</b></a></li>
                    
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
</style>
