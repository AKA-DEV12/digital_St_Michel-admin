@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 fw-bold mb-1">Rôles & Permissions</h1>
            <p class="text-secondary">Définissez les niveaux d'accès pour les menus de l'administration.</p>
        </div>
        <button class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#roleModal">
            <i class="fa-solid fa-plus me-2"></i> Nouveau Rôle
        </button>
    </div>
</div>

<div class="row g-4">
    @foreach($roles as $role)
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-hover">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-solid fa-shield-halved small"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $role->name }}</h5>
                    </div>
                    <span class="small text-secondary">{{ $role->permissions->count() }} accès configurés</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-white border rounded-3" data-bs-toggle="dropdown">
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
            <div class="card-body px-4 py-2 bg-slate-50">
                <div class="d-flex flex-wrap gap-2 mb-4 mt-2">
                    @forelse($role->permissions as $perm)
                        <span class="badge bg-white text-slate-700 border rounded-pill px-3 py-2 small fw-semibold shadow-sm">
                            {{ $translations[$perm->name] ?? $perm->name }}
                        </span>
                    @empty
                        <div class="text-center w-100 py-4 opacity-50 fst-italic small">Aucune permission assignée</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-slate-900 text-white border-0 py-4 px-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <h5 class="modal-title fw-bold" id="roleModalTitle">Nouveau Rôle</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="roleForm" method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div id="role-method-field"></div>
                <div class="modal-body p-6 bg-slate-50">
                    <div class="card border-0 rounded-4 shadow-sm mb-5">
                        <div class="card-body p-4">
                            <label class="form-label small fw-bold text-slate-700 text-uppercase" style="letter-spacing: 1px;">Identification du rôle</label>
                            <input type="text" name="name" id="role_name" class="form-control rounded-3 py-3 border-light bg-slate-50 fw-bold" placeholder="Ex: Gestionnaire de Messes" required>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold text-slate-800 mb-4 px-2 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i> 
                        Permissions et Accès aux Menus
                    </h6>

                    <div class="row g-4">
                        @foreach($permissionGroups as $groupName => $perms)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 rounded-4 h-100 shadow-sm overflow-hidden">
                                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                    <h6 class="fw-bold text-primary mb-0 small text-uppercase">{{ $groupName }}</h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="space-y-3 d-flex flex-column gap-2">
                                        @foreach($perms as $permName)
                                            @php 
                                                $perm = $allPermissions->where('name', $permName)->first();
                                            @endphp
                                            @if($perm)
                                            <div class="form-check custom-check p-0 mb-0">
                                                <input class="btn-check" type="checkbox" name="permissions[]" id="perm-{{ $perm->id }}" value="{{ $perm->name }}">
                                                <label class="btn btn-outline-slate-200 w-100 text-start rounded-3 py-2 px-3 d-flex align-items-center gap-3 transition-all" for="perm-{{ $perm->id }}">
                                                    <div class="check-box-indicator">
                                                        <i class="fa-solid fa-check small"></i>
                                                    </div>
                                                    <span class="small fw-bold">{{ $translations[$perm->name] ?? $perm->name }}</span>
                                                </label>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 p-4 shadow-sm">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold shadow">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer le rôle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editRole(role, rolePermissions) {
    const form = document.getElementById('roleForm');
    form.action = `/roles/${role.id}`;
    document.getElementById('roleModalTitle').innerText = 'Modifier le rôle : ' + role.name;
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
    .text-slate-700 { color: #334155; }
    .btn-outline-slate-200 { 
        border: 1px solid #e2e8f0;
        color: #64748b;
        background: white;
    }
    .btn-outline-slate-200:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #475569;
    }
    
    .btn-check:checked + .btn-outline-slate-200 {
        background-color: #f0fdf4 !important;
        border-color: #22c55e !important;
        color: #15803d !important;
    }
    
    .check-box-indicator {
        width: 18px;
        height: 18px;
        border: 2px solid #e2e8f0;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .btn-check:checked + .btn-outline-slate-200 .check-box-indicator {
        background-color: #22c55e;
        border-color: #22c55e;
        color: white;
    }
    
    .check-box-indicator i {
        display: none;
    }
    
    .btn-check:checked + .btn-outline-slate-200 .check-box-indicator i {
        display: block;
    }

    .transition-hover {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px -5px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
