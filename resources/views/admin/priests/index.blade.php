@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Gestion du Clergé</h1>
    <p class="text-secondary">Ajoutez et gérez les prêtres disponibles pour les demandes de rendez-vous.</p>
</div>

<x-data-table :headers="['Photo', 'Nom complet', 'Rôle & Audience', 'Statut', 'Actions']" :collection="$priests">
    <x-slot name="title">Liste des prêtres</x-slot>
    
    <x-slot name="actions">
        <a href="{{ route('admin.priests.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 text-white fw-600 shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Ajouter un prêtre
        </a>
    </x-slot>

    @foreach($priests as $priest)
    <tr>
        <td class="px-6 py-4">
            @if($priest->photo_path && file_exists(public_path($priest->photo_path)))
                <img src="{{ asset($priest->photo_path) }}" alt="{{ $priest->first_name }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
            @else
                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $priest->first_name }} {{ $priest->last_name }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $priest->role }}</div>
            <div class="text-xs text-secondary mt-1 text-truncate" style="max-width: 250px;">{{ $priest->audience ? 'Audience: ' . $priest->audience : 'Toutes audiences' }}</div>
        </td>
        <td class="px-6 py-4">
            @if($priest->is_active)
                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1 border-0">Actif</span>
            @else
                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1 border-0">Inactif</span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li>
                        <a href="{{ route('admin.priests.edit', $priest) }}" class="dropdown-item small py-2 rounded-2 text-primary fw-bold mb-1">
                            <i class="fa-solid fa-pen-to-square me-2"></i> MODIFIER
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('admin.priests.toggle', $priest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="dropdown-item small py-2 rounded-2 text-warning fw-bold mb-1">
                                <i class="fa-solid fa-power-off me-2"></i> {{ $priest->is_active ? 'DÉSACTIVER' : 'ACTIVER' }}
                            </button>
                        </form>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('admin.priests.destroy', $priest) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression de ce prêtre ?')">
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

<style>
    .bg-success-subtle { background-color: #f0fdf4 !important; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .text-success { color: #16a34a !important; }
    .text-danger { color: #dc2626 !important; }
</style>
@endsection
