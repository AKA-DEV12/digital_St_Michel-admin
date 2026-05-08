@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Membres des Groupes</h1>
    <p class="text-secondary">Gérez les membres des différents groupes et mouvements paroissiaux.</p>
    
    @if(!auth()->user()->hasRole('Super Admin'))
        <div class="alert alert-info d-flex align-items-center" role="alert">
            <i class="fa-solid fa-info-circle me-2"></i>
            <div>
                <strong>Accès limité :</strong> Vous ne voyez que les membres de votre groupe ou ceux que vous avez ajoutés.
            </div>
        </div>
    @endif
</div>

<x-data-table :headers="['Photo', 'Nom & Prénom', 'Date Adhésion', 'Responsabilité', 'Situation Matrimoniale', 'Actions']" :collection="$members">
    <x-slot name="title">Liste des membres</x-slot>
    
    <x-slot name="actions">
        <a href="{{ route('group-members.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-2 text-white fw-600 shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Ajouter un membre
        </a>
    </x-slot>

    @foreach($members as $member)
    <tr>
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <img src="{{ $member->photo_url }}" alt="{{ $member->nom_prenom }}" 
                     class="rounded-3 me-3" style="width: 40px; height: 40px; object-fit: cover;" 
                     onerror="this.style.display='none'; this.parentElement.innerHTML='<div class=\\'rounded-3 bg-gray-100 d-flex align-items-center justify-content-center me-3\\' style=\\'width: 40px; height: 40px;\\'><i class=\\'fa-solid fa-user text-gray-400 fs-6\\'></i></div>';">
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $member->nom_prenom }}</div>
        </td>
        <td class="px-6 py-4">
            <span class="text-secondary small">{{ $member->formatted_date }}</span>
        </td>
        <td class="px-6 py-4">
            <span class="badge bg-light text-dark small">{{ $member->responsabilite ?: '-' }}</span>
        </td>
        <td class="px-6 py-4">
            <span class="badge bg-primary bg-opacity-10 text-primary small">{{ $member->situation_matrimoniale }}</span>
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li>
                        <a href="{{ route('group-members.show', $member) }}" class="dropdown-item small py-2 rounded-2 text-primary">
                            <i class="fa-solid fa-eye me-2"></i> Voir
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('group-members.edit', $member) }}" class="dropdown-item small py-2 rounded-2 text-primary">
                            <i class="fa-solid fa-pen me-2"></i> Modifier
                        </a>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('group-members.destroy', $member) }}" method="POST" onsubmit="return confirm('Supprimer ce membre ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger">
                                <i class="fa-solid fa-trash me-2"></i> Supprimer
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
