@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <h1 class="h3 fw-bold mb-1">Rôles & Permissions</h1>
    <p class="text-secondary">Définissez les niveaux d'accès pour les menus de l'administration.</p>
</div>

<div class="row g-4">
    @foreach($roles as $role)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ $role->name }}</h5>
                    <span class="small text-secondary">{{ $role->permissions->count() }} permissions</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light rounded-3" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2">
                        <li>
                            <button class="dropdown-item small py-2 rounded-2 text-primary fw-bold" 
                                    onclick="editRole({{ json_encode($role) }}, {{ json_encode($role->permissions->pluck('name')) }})"
                                    data-bs-toggle="modal" data-bs-target="#roleModal">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Modifier
                            </button>
                        </li>
                        @if($role->name !== 'Super Admin')
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Supprimer ce rôle ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item small py-2 rounded-2 text-danger fw-bold bg-danger-subtle">
                                    <i class="fa-solid fa-trash me-2"></i> Supprimer
                                </button>
                            </form>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="card-body px-4 py-2">
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @foreach($role->permissions as $perm)
                        <span class="badge bg-slate-100 text-slate-600 border-0 rounded-pill px-2 py-1 small fw-normal">
                            {{ str_replace('access_', '', $perm->name) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Add Role Card -->
    <div class="col-md-6 col-xl-4">
        <button class="card shadow-sm rounded-4 overflow-hidden h-100 w-100 text-center d-flex align-items-center justify-content-center bg-slate-50 border-dashed border-2 py-5" 
                data-bs-toggle="modal" data-bs-target="#roleModal" style="border-style: dashed !important; border-color: #cbd5e1 !important;">
            <div class="py-4">
                <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-plus text-primary"></i>
                </div>
                <h6 class="fw-bold text-slate-700">Nouveau Rôle</h6>
                <p class="small text-secondary mb-0">Définir des nouveaux accès</p>
            </div>
        </button>
    </div>
</div>

<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-slate-900 text-white border-0 py-4 px-6">
                <h5 class="modal-title fw-bold" id="roleModalTitle">Nouveau Rôle</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="roleForm" method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div id="role-method-field"></div>
                <div class="modal-body p-6">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-slate-700">Nom du rôle</label>
                        <input type="text" name="name" id="role_name" class="form-control rounded-3 py-2" placeholder="Ex: Gestionnaire Inscriptions" required>
                    </div>
                    
                    <label class="form-label small fw-bold text-slate-700 mb-3">Sélectionnez les accès (Menus)</label>
                    <div class="row g-3">
                        @foreach($permissions as $perm)
                        <div class="col-md-4">
                            <div class="form-check custom-check p-0">
                                <input class="btn-check" type="checkbox" name="permissions[]" id="perm-{{ $perm->id }}" value="{{ $perm->name }}">
                                <label class="btn btn-outline-slate-200 w-100 text-start rounded-3 py-2 px-3 d-flex align-items-center gap-2" for="perm-{{ $perm->id }}">
                                    <i class="fa-solid fa-circle-check opacity-20"></i>
                                    <span class="small fw-bold">{{ ucfirst(str_replace('_', ' ', str_replace('access_', '', $perm->name))) }}</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
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
function editRole(role, rolePermissions) {
    const form = document.getElementById('roleForm');
    form.action = `/roles/${role.id}`;
    document.getElementById('roleModalTitle').innerText = 'Modifier le rôle';
    document.getElementById('role-method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('role_name').value = role.name;

    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
        cb.checked = rolePermissions.includes(cb.value);
    });
}

document.getElementById('roleModal').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || (event.relatedTarget.getAttribute('data-bs-target') === '#roleModal' && !event.relatedTarget.onclick)) {
        const form = document.getElementById('roleForm');
        form.action = "{{ route('roles.store') }}";
        document.getElementById('roleModalTitle').innerText = 'Nouveau Rôle';
        document.getElementById('role-method-field').innerHTML = '';
        form.reset();
        document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
    }
});
</script>

<style>
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-100 { background-color: #f1f5f9; }
    .text-slate-600 { color: #475569; }
    .btn-outline-slate-200 { 
        border: 1px solid #e2e8f0;
        color: #64748b;
    }
    .btn-check:checked + .btn-outline-slate-200 {
        background-color: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }
    .btn-check:checked + .btn-outline-slate-200 i {
        opacity: 1;
    }
</style>
@endsection
