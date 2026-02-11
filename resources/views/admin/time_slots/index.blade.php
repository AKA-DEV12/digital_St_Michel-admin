@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Créneaux Horaires</h1>
    <p class="text-secondary">Définissez les intervalles de temps disponibles pour les réservations.</p>
</div>

<div class="row g-4 animate-fade-in">
    <!-- Add Slot Form -->
    <div class="col-md-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sticky-top" style="top: 2rem; z-index: 10;">
            <h5 class="fw-bold mb-4">Nouveau Créneau</h5>
            <form action="{{ route('time_slots.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-600 small">Heure de début</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-start-3 px-3"><i class="fa-regular fa-clock text-primary"></i></span>
                        <input type="time" name="start_time" class="form-control bg-light border-0 rounded-end-3 py-2" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-600 small">Heure de fin</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 rounded-start-3 px-3"><i class="fa-regular fa-clock text-primary"></i></span>
                        <input type="time" name="end_time" class="form-control bg-light border-0 rounded-end-3 py-2" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm fw-bold">
                    <i class="fa-solid fa-plus me-2"></i> Ajouter au planning
                </button>
            </form>
        </div>
    </div>

    <!-- List Slots -->
    <div class="col-md-8">
        <x-data-table :headers="['Horaires', 'Durée', 'Actions']" :collection="$timeSlots">
            <x-slot name="title">Planning actuel</x-slot>
            
            @foreach($timeSlots as $slot)
            <tr>
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 bg-primary-light text-primary px-3 py-2 fw-bold d-flex align-items-center justify-content-center" style="font-size: 0.9rem; min-width: 80px;">
                            {{ $slot->start_time }}
                        </div>
                        <div class="mx-3 opacity-20">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <div class="rounded-3 bg-light text-dark border-0 px-3 py-2 fw-bold d-flex align-items-center justify-content-center" style="font-size: 0.9rem; min-width: 80px;">
                            {{ $slot->end_time }}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center text-secondary small">
                        <div class="bg-gray-50 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                            <i class="fa-solid fa-hourglass-start opacity-50" style="font-size: 0.75rem;"></i> 
                        </div>
                        @php
                            $start = \Carbon\Carbon::parse($slot->start_time);
                            $end = \Carbon\Carbon::parse($slot->end_time);
                            echo $start->diffInMinutes($end) . ' min';
                        @endphp
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                            <span class="small fw-bold">Action</span>
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                            <li>
                                <form action="{{ route('time_slots.destroy', $slot) }}" method="POST" onsubmit="return confirm('Retirer ce créneau du planning ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger">
                                        <i class="fa-solid fa-trash me-2"></i> Retirer
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-data-table>
    </div>
</div>
@endsection

<style>
    .rounded-xl { border-radius: 1rem !important; }
</style>
