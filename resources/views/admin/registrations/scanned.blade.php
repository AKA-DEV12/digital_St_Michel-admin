@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <h1 class="h3 fw-bold mb-1">Présences (QR Code Scannés)</h1>
    <p class="text-secondary">Liste des participants ayant validé leur présence via le scan QR Code.</p>
</div>

<x-data-table :headers="['Participant', 'Activité', 'Date de Scan', 'Statut Scan']" :collection="$registrations">
    <x-slot name="title">Participants Scannés</x-slot>
    
    @forelse($registrations as $reg)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-emerald-100 text-emerald-600 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $reg->full_name }}</div>
                    <div class="small text-secondary">{{ $reg->phone_number }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark fw-600">{{ $reg->registrationActivity->title ?? 'N/A' }}</div>
            <div class="small text-secondary">{{ $reg->option }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark">
                <i class="fa-regular fa-calendar-check me-1 opacity-50"></i>
                {{ $reg->updated_at->format('d/m/Y') }}
            </div>
            <div class="small text-secondary">
                <i class="fa-regular fa-clock me-1 opacity-50"></i>
                {{ $reg->updated_at->format('H:i') }}
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="badge rounded-pill bg-emerald-500 text-white px-3 py-1 border-0 shadow-sm fw-bold">SCANNÉ</span>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="4" class="text-center py-5">
            <div class="text-secondary opacity-50 mb-3">
                <i class="fa-solid fa-qrcode fs-1"></i>
            </div>
            <h5 class="text-secondary fw-bold">Aucun QR code scanné pour le moment</h5>
            <p class="text-muted small">Les participants validés apparaîtront ici.</p>
        </td>
    </tr>
    @endforelse
</x-data-table>

<style>
    .bg-emerald-100 { background-color: #d1fae5; }
    .text-emerald-600 { color: #059669; }
    .bg-emerald-500 { background-color: #10b981; }
</style>
@endsection
