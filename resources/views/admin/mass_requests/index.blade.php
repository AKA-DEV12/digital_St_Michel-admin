@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Demandes de Messe</h1>
                <p class="text-secondary">Gérez les intentions de messe reçues en ligne.</p>
            </div>
            <a href="{{ route('admin.mass_requests.config') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-cog me-2"></i> Configuration
            </a>
        </div>
    </div>

    <!-- Premium Wallet Cards -->
    <div class="row mb-5 animate-fade-in justify-content-center g-4">
        <!-- Confirmed Wallet -->
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card border-0 rounded-4 overflow-hidden shadow-lg position-relative premium-wallet-card">
                <div class="position-absolute top-0 end-0 p-3 opacity-25" style="transform: translate(20%, -20%) scale(2);">
                    <i class="fa-solid fa-church text-white" style="font-size: 8rem;"></i>
                </div>
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-vault text-white"></i>
                            </div>
                            <h6 class="text-white text-uppercase fw-bold mb-0" style="letter-spacing: 1px; font-size: 0.85rem;">Portefeuille Messes</h6>
                        </div>
                        <span class="badge bg-white text-danger rounded-pill px-3 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-circle-check text-success me-1"></i> Validé
                        </span>
                    </div>
                    <div class="mb-1">
                        <span class="text-white-50 small fw-bold text-uppercase">Solde Encaissé</span>
                        <h2 class="display-6 fw-bolder text-white mb-0">
                            {{ number_format($walletTotal, 0, ',', ' ') }} <span class="fs-5 opacity-75">FCFA</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Wallet -->
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card border-0 rounded-4 overflow-hidden shadow-lg position-relative premium-wallet-secondary">
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
                        <span class="text-white-50 small fw-bold text-uppercase">Montant Potentiel</span>
                        <h2 class="display-6 fw-bolder text-white mb-0">
                            {{ number_format($pendingWalletTotal, 0, ',', ' ') }} <span class="fs-5 opacity-75">FCFA</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-data-table :headers="['Demandeur', 'Date & Heures', 'Intention', 'Montant', 'Statut', 'Actions']" :collection="$requests">
        <x-slot name="title">Liste des demandes</x-slot>

        <x-slot name="actions">
            <div class="btn-group glass-card mb-0 p-1 d-flex gap-1 border-0 bg-gray-50" style="border-radius: 12px;">
                @php 
                    $currentStatus = request('status', 'pending');
                @endphp
                <a href="{{ route('admin.mass_requests.index', ['status' => 'pending']) }}" class="btn btn-xs {{ $currentStatus == 'pending' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                    En attente
                </a>
                <a href="{{ route('admin.mass_requests.index', ['status' => 'confirmed']) }}" class="btn btn-xs {{ $currentStatus == 'confirmed' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                    Confirmées
                </a>
                <a href="{{ route('admin.mass_requests.index', ['status' => 'cancelled']) }}" class="btn btn-xs {{ $currentStatus == 'cancelled' ? 'btn-primary' : 'btn-light bg-transparent border-0' }} rounded-3 px-2 py-1 small" style="font-size: 0.75rem;">
                    Annulées
                </a>
            </div>
        </x-slot>

        @foreach($requests as $req)
            <tr class="group">
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                            {{ strtoupper(substr($req->name1, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $req->name1 }}</div>
                            <div class="text-xs text-secondary mt-1">
                                <i class="fa-solid fa-envelope me-1 opacity-50"></i> {{ $req->email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark">{{ $req->requested_date->format('d/m/Y') }}</div>
                    <div class="text-xs text-secondary mt-1">
                        <i class="fa-solid fa-clock me-1 opacity-50"></i> {{ implode(', ', $req->time_slots ?? []) }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-truncate" style="max-width: 200px;" title="{{ $req->request_object }}">
                        {{ $req->request_object }}
                    </div>
                    <div class="text-xs text-secondary mt-1">
                        {{ $req->name2 ? '+ '.$req->name2 : '' }} {{ $req->name3 ? '+ '.$req->name3 : '' }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-primary">{{ number_format($req->amount, 0, ',', ' ') }} FCFA</div>
                </td>
                <td class="px-6 py-4">
                    @if($req->status == 'pending')
                        <span class="badge rounded-pill bg-warning text-white px-3 py-1 fw-bold">En attente</span>
                    @elseif($req->status == 'confirmed')
                        <span class="badge rounded-pill bg-success text-white px-3 py-1 fw-bold">Confirmée</span>
                    @else
                        <span class="badge rounded-pill bg-danger text-white px-3 py-1 fw-bold">Annulée</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-white border text-secondary rounded-3 px-3 py-2 shadow-sm" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                            <li><a class="dropdown-item small py-2 rounded-2 fw-bold" href="{{ route('admin.mass_requests.show', $req->id) }}"><i class="fa-solid fa-eye me-2"></i>Détails</a></li>
                            @if($req->status == 'pending')
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form action="{{ route('admin.mass_requests.validate', $req->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item small py-2 rounded-2 text-success fw-bold">
                                        <i class="fa-solid fa-check me-2"></i> VALIDER
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.mass_requests.cancel', $req->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold">
                                        <i class="fa-solid fa-xmark me-2"></i> ANNULER
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
    .premium-wallet-card {
        background: linear-gradient(135deg, #dc143c 0%, #be123c 100%);
        box-shadow: 0 10px 25px -5px rgba(220, 20, 60, 0.4) !important;
    }
    .premium-wallet-secondary {
        background: linear-gradient(135deg, #475569 0%, #1e293b 100%);
        box-shadow: 0 10px 25px -5px rgba(30, 41, 59, 0.4) !important;
    }
</style>
