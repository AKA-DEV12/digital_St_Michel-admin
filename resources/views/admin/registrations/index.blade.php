@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Inscriptions</h1>
                <p class="text-secondary">Gérez les inscriptions aux activités et pèlerinages.</p>
            </div>
        </div>
    </div>

    <x-data-table :headers="['Nom', 'Activité', 'Option', 'Montant', 'Statut', 'Actions']" :collection="$registrations">
        <x-slot name="title">Liste des inscriptions</x-slot>

        <x-slot name="actions">
            <div class="btn-group glass-card mb-0 p-1 d-flex gap-1 border-0 bg-gray-50" style="border-radius: 12px;">
                @php $currentStatus = request('status', 'pending'); @endphp
                <a href="{{ route('admin.registrations.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $currentStatus == 'pending' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                    En attente
                </a>
                <a href="{{ route('admin.registrations.index', ['status' => 'confirmed']) }}" class="btn btn-xs {{ $currentStatus == 'confirmed' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                    Confirmées
                </a>
                <a href="{{ route('admin.registrations.index', ['status' => 'cancelled']) }}" class="btn btn-xs {{ $currentStatus == 'cancelled' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                    Annulées
                </a>
            </div>
        </x-slot>

        <x-slot name="advancedSearch">
            <form action="{{ route('admin.registrations.index') }}" method="GET">
                @foreach(request()->except(['activity_id', 'status', 'page']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-secondary">Filtrer par activité</label>
                        <select name="activity_id" class="form-select form-select-sm rounded-3">
                            <option value="">Toutes les activités</option>
                            @foreach($activities as $activity)
                                <option value="{{ $activity->id }}" {{ request('activity_id') == $activity->id ? 'selected' : '' }}>
                                    {{ $activity->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-sm btn-primary w-100 rounded-3 py-2 fw-bold">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </x-slot>

        @foreach($registrations as $reg)
            <tr class="group">
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                            {{ strtoupper(substr($reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'G'), 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">
                                {{ $reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'Groupe non nommé') }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark">{{ $reg->registrationActivity->title ?? 'N/A' }}</div>
                    <div class="text-xs text-secondary mt-1">
                        <i class="fa-solid fa-phone me-1 opacity-50"></i> {{ $reg->primary_phone }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="badge bg-light text-dark border">{{ $reg->option }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-primary">{{ number_format($reg->total_amount, 0, ',', ' ') }} FCFA</div>
                </td>
                <td class="px-6 py-4">
                    @if($reg->status == 'pending')
                        <span class="badge rounded-pill bg-warning text-white px-3 py-1 border-0 shadow-sm fw-bold">En attente</span>
                    @elseif($reg->status == 'confirmed')
                        <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold">Confirmée</span>
                    @else
                        <span class="badge rounded-pill bg-danger text-white px-3 py-1 border-0 shadow-sm fw-bold">Annulée</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                                <li><a class="dropdown-item small py-2 rounded-2 fw-bold" href="{{ route('admin.registrations.show', $reg->uuid) }}"><i class="fa-solid fa-eye me-2"></i>Détails</a></li>

                            @if($reg->status == 'pending')
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('admin.registrations.update_status', $reg->uuid) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="dropdown-item small py-2 rounded-2 text-success fw-bold bg-success-subtle mb-1">
                                            <i class="fa-solid fa-circle-check me-2"></i> CONFIRMER
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('admin.registrations.update_status', $reg->uuid) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="cancelled">
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
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .text-success { color: #16a34a !important; }
    .text-danger { color: #dc2626 !important; }
</style>

