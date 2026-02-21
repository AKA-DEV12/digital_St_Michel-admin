@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold mb-1">Groupes Constitués</h1>
            <p class="text-secondary">Gérez les super-groupes formés à partir des inscriptions confirmées.</p>
        </div>
        <a href="{{ route('admin.participant_groups.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm">
            <i class="fa-solid fa-layer-group me-2"></i> Constituer un groupe
        </a>
    </div>
</div>

<x-data-table :headers="['Nom du groupe', 'Objectif de taille', 'Inscriptions Actuelles', 'Statut', 'Date de création']" :collection="$groups">
    <x-slot name="title">Liste des groupes</x-slot>

    @foreach($groups as $group)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $group->name }}</div>
                    <div class="small text-secondary">#{{ $group->id }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $group->target_size }} personnes</div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-primary">{{ $group->registrations_count }} inscription(s) associée(s)</div>
        </td>
        <td class="px-6 py-4">
            <span class="badge rounded-pill bg-success text-white px-3 py-1 border-0 shadow-sm fw-bold">Complet</span>
        </td>
        <td class="px-6 py-4">
            <div class="text-secondary small mb-2">
                {{ $group->created_at->format('d/m/Y H:i') }}
            </div>
            <a href="{{ route('admin.participant_groups.show', $group->id) }}" class="btn btn-sm btn-light border-0 text-primary fw-bold rounded-pill px-3">
                <i class="fa-solid fa-eye me-1"></i> Voir détails
            </a>
        </td>
    </tr>
    @endforeach
</x-data-table>

@endsection
