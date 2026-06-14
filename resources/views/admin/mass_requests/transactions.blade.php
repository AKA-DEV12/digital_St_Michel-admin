@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-1">Identifiants de Transaction</h1>
                <p class="text-secondary">Liste des identifiants de transactions liés aux demandes de messe validées.</p>
            </div>
            <a href="{{ route('admin.mass_requests.index') }}" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-bold shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Retour aux demandes
            </a>
        </div>
    </div>

    <x-data-table :headers="['ID Transaction', 'Demandeur', 'Montant', 'Validation', 'Actions']" :collection="$requests">
        <x-slot name="title">Transactions validées</x-slot>

        @foreach($requests as $req)
            <tr class="group">
                <td class="px-6 py-4">
                    <span class="badge bg-white text-dark border border-gray-200 px-3 py-2 rounded-3 font-monospace fw-bold shadow-sm" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-receipt text-primary me-2"></i>{{ $req->transaction_id }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                            {{ strtoupper(substr($req->name1, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $req->name1 }}</div>
                            <div class="text-xs text-secondary mt-1">
                                <i class="fa-solid fa-phone me-1 opacity-50"></i> {{ $req->phone }}
                            </div>
                            <div class="text-xs text-secondary">
                                <i class="fa-solid fa-envelope me-1 opacity-50"></i> {{ $req->email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-primary">{{ number_format($req->amount, 0, ',', ' ') }} FCFA</div>
                </td>
                <td class="px-6 py-4">
                    <div class="fw-bold text-dark">
                        <i class="fa-regular fa-calendar-check text-success me-1"></i>
                        {{ $req->validated_at ? $req->validated_at->format('d/m/Y') : '-' }}
                    </div>
                    <div class="text-xs text-secondary mt-1">
                        <i class="fa-regular fa-clock me-1 opacity-50"></i>
                        {{ $req->validated_at ? $req->validated_at->format('H:i') : '-' }}
                    </div>
                </td>
                <td class="px-6 py-4 text-end">
                    <a href="{{ route('admin.mass_requests.show', $req->id) }}" class="btn btn-sm btn-white border text-secondary rounded-3 px-3 py-2 shadow-sm fw-bold">
                        <i class="fa-solid fa-eye me-1"></i> Voir la demande
                    </a>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
