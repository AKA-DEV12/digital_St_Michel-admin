@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Inscriptions</h1>
                <p class="text-secondary">Gérez les inscriptions aux activités et pèlerinages.</p>
            </div>
            <a href="{{ route('admin.participant_groups.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-layer-group me-2"></i> Constituer un groupe
            </a>
        </div>
    </div>

    <!-- Premium Wallet Cards -->
    <div class="row mb-5 animate-fade-in justify-content-center g-4" style="animation-delay: 0.1s">
        <!-- Confirmed Wallet -->
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card border-0 rounded-4 overflow-hidden shadow-lg position-relative premium-wallet-card">
                <!-- Background Decoration -->
                <div class="position-absolute top-0 end-0 p-3 opacity-25" style="transform: translate(20%, -20%) scale(2);">
                    <i class="fa-solid fa-wallet text-white" style="font-size: 8rem;"></i>
                </div>
                
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-vault text-white"></i>
                            </div>
                            <h6 class="text-white text-uppercase fw-bold mb-0" style="letter-spacing: 1px; font-size: 0.85rem;">Portefeuille Global</h6>
                        </div>
                        <span class="badge bg-white text-danger rounded-pill px-3 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Validé
                        </span>
                    </div>

                    <div class="mb-1">
                        <span class="text-white-50 small fw-bold text-uppercase">Solde Disponible</span>
                        <h2 class="display-6 fw-bolder text-white mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            {{ number_format($walletTotal, 0, ',', ' ') }} <span class="fs-5 opacity-75">FCFA</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Wallet -->
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card border-0 rounded-4 overflow-hidden shadow-lg position-relative premium-wallet-secondary">
                <!-- Background Decoration -->
                <div class="position-absolute top-0 end-0 p-3 opacity-25" style="transform: translate(20%, -20%) scale(2);">
                    <i class="fa-solid fa-hourglass-half text-white" style="font-size: 8rem;"></i>
                </div>
                
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-vault text-white"></i>
                            </div>
                            <h6 class="text-white text-uppercase fw-bold mb-0" style="letter-spacing: 1px; font-size: 0.85rem;">En Attente</h6>
                        </div>
                        <span class="badge bg-white align-items-center d-flex gap-1 text-secondary rounded-pill px-3 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-clock text-warning"></i> Non validé
                        </span>
                    </div>

                    <div class="mb-1">
                        <span class="text-white-50 small fw-bold text-uppercase">Solde Potentiel</span>
                        <h2 class="display-6 fw-bolder text-white mb-0" style="text-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                            {{ number_format($pendingWalletTotal, 0, ',', ' ') }} <span class="fs-5 opacity-75">FCFA</span>
                        </h2>
                    </div>
                </div>
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
                            @if($reg->participant_group_id && $reg->participantGroup)
                                {{ strtoupper(substr($reg->participantGroup->name, 0, 1)) }}
                            @else
                                {{ strtoupper(substr($reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'G'), 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="fw-bold text-dark">
                                @if($reg->participant_group_id && $reg->participantGroup)
                                    {{ $reg->participantGroup->name }}
                                @else
                                    {{ $reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'Groupe non nommé') }}
                                @endif
                            </div>
                            @if($reg->participant_group_id && $reg->participantGroup)
                                <div class="text-xs text-secondary mt-1">
                                    Init: {{ $reg->option === 'Individuel' ? $reg->primary_name : ($reg->group_name ?? 'Inscription multiple') }}
                                </div>
                            @endif
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
                    <span class="badge bg-light text-dark border mb-1">{{ $reg->option }}</span>
                    @if($reg->participant_group_id)
                        <div class="mt-1">
                            <a href="{{ route('admin.participant_groups.show', $reg->participant_group_id) }}" class="badge bg-primary bg-opacity-10 text-primary border-0 text-decoration-none" title="Fait partie du groupe">
                                <i class="fa-solid fa-layer-group me-1"></i> {{ $reg->participantGroup->name ?? 'Groupe #' . $reg->participant_group_id }}
                            </a>
                        </div>
                    @endif
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
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('admin.registrations.destroy', $reg->uuid) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ? Cette action est irréversible.');">
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
@endsection

<style>
    .premium-wallet-card {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.5) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-wallet-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(220, 38, 38, 0.6) !important;
    }
    .premium-wallet-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        box-shadow: 0 10px 25px -5px rgba(71, 85, 105, 0.5) !important;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .premium-wallet-secondary:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(71, 85, 105, 0.6) !important;
    }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .text-success { color: #16a34a !important; }
    .text-danger { color: #dc2626 !important; }
</style>

