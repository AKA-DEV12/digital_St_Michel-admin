@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <h1 class="h3 fw-bold mb-1">Gestion des Utilisateurs</h1>
    <p class="text-secondary">Gérez les accès administratifs et attribuez des rôles.</p>
</div>

<x-data-table :headers="['Nom', 'Email', 'Rôles', 'Date d\'ajout', 'Action']" :collection="$users">
    <x-slot name="title">Administrateurs</x-slot>
    
    <x-slot name="actions">
        <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#userModal">
            <i class="fa-solid fa-user-plus me-2"></i> Ajouter un administrateur
        </button>
    </x-slot>

    @foreach($users as $user)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="fw-bold text-dark">{{ $user->name }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark">{{ $user->email }}</div>
        </td>
        <td class="px-6 py-4">
            @foreach($user->roles as $role)
                <span class="badge bg-primary bg-opacity-10 text-primary border-0 rounded-pill px-3 py-1 small fw-bold me-1">
                    {{ $role->name }}
                </span>
            @endforeach
        </td>
        <td class="px-6 py-4 text-secondary small">
            {{ $user->created_at->format('d/m/Y') }}
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li>
                        <button class="dropdown-item small py-2 rounded-2 text-primary fw-bold" 
                                onclick="editUser({{ json_encode($user) }}, {{ json_encode($user->roles->pluck('name')) }})"
                                data-bs-toggle="modal" data-bs-target="#userModal">
                            <i class="fa-solid fa-pen-to-square me-2"></i> MODIFIER
                        </button>
                    </li>
                    @if($user->id !== auth()->id())
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold bg-danger-subtle">
                                <i class="fa-solid fa-trash me-2"></i> SUPPRIMER
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

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-slate-900 text-white border-0 py-4 px-6">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvel Utilisateur</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST" action="{{ route('users.store') }}">
                @csrf
                <div id="method-field"></div>
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-slate-700">Nom complet</label>
                        <input type="text" name="name" id="name" class="form-control rounded-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-slate-700">Email</label>
                        <input type="email" name="email" id="email" class="form-control rounded-3 py-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-slate-700">Mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control rounded-3 py-2" placeholder="Min. 8 caractères">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-slate-700 mb-2">Attribuer des rôles</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($roles as $role)
                            <div class="form-check form-check-inline m-0">
                                <input class="btn-check" type="checkbox" name="roles[]" id="role-{{ $role->id }}" value="{{ $role->name }}">
                                <label class="btn btn-outline-primary btn-sm rounded-pill px-3" for="role-{{ $role->id }}">
                                    {{ $role->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-0 p-4">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(user, userRoles) {
    const form = document.getElementById('userForm');
    form.action = `/users/${user.id}`;
    document.getElementById('modalTitle').innerText = 'Modifier l\'utilisateur';
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('name').value = user.name;
    document.getElementById('email').value = user.email;
    document.getElementById('password').required = false;
    document.getElementById('password').placeholder = "Laisser vide pour conserver l'actuel";

    // Reset and check roles
    document.querySelectorAll('input[name="roles[]"]').forEach(cb => {
        cb.checked = userRoles.includes(cb.value);
    });
}

document.getElementById('userModal').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || (event.relatedTarget.getAttribute('data-bs-target') === '#userModal' && !event.relatedTarget.onclick)) {
        const form = document.getElementById('userForm');
        form.action = "{{ route('users.store') }}";
        document.getElementById('modalTitle').innerText = 'Nouvel Utilisateur';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('password').required = true;
        document.getElementById('password').placeholder = "Min. 8 caractères";
        form.reset();
        document.querySelectorAll('input[name="roles[]"]').forEach(cb => cb.checked = false);
    }
});
</script>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-900 { background-color: #0f172a; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
</style>
@endsection
