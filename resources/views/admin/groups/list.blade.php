@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <h1 class="h3 fw-bold mb-1">Groupes Paroissiaux</h1>
    <p class="text-secondary">Gérez les groupes et mouvements de la paroisse.</p>
</div>

<x-data-table :headers="['Nom du Groupe', 'Nombre de Membres', 'Créé le', 'Actions']" :collection="$groups">
    <x-slot name="title">Liste des groupes paroissiaux</x-slot>
    
    <x-slot name="actions">
        <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 text-white fw-600 shadow-sm" data-bs-toggle="modal" data-bs-target="#addGroupModal">
            <i class="fa-solid fa-plus me-2"></i> Nouveau groupe
        </button>
    </x-slot>

    @foreach($groups as $group)
    <tr>
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <div class="fw-bold text-dark">{{ $group->nom_groupe }}</div>
            </div>
        </td>
        <td class="px-6 py-4">
            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold">{{ $group->members_count }}</span>
        </td>
        <td class="px-6 py-4">
            <span class="text-secondary small">{{ $group->created_at->format('d/m/Y') }}</span>
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li>
                        <button class="dropdown-item small py-2 rounded-2 text-primary" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editGroupModal{{ $group->id }}">
                            <i class="fa-solid fa-pen me-2"></i> Modifier
                        </button>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Supprimer ce groupe ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger">
                                <i class="fa-solid fa-trash me-2"></i> Supprimer
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade text-start" id="editGroupModal{{ $group->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-0 pb-0 p-4">
                            <h5 class="modal-title fw-bold">Modifier le groupe</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('groups.update', $group) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4 py-3">
                                <div class="mb-3 text-start">
                                    <label class="form-label fw-600 small">Nom du groupe</label>
                                    <input type="text" name="nom_groupe" class="form-control rounded-3 py-2" value="{{ $group->nom_groupe }}" required>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4 pt-0">
                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Mettre à jour</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    @endforeach
</x-data-table>

<!-- Add Modal -->
<div class="modal fade" id="addGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0 p-4">
                <h5 class="modal-title fw-bold">Nouveau Groupe Paroissial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('groups.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-600 small">Nom du groupe</label>
                        <input type="text" name="nom_groupe" class="form-control rounded-3 py-2" placeholder="ex: Groupe de prière" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
