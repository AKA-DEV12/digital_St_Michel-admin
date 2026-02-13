@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in text-slate-900">
    <h1 class="h3 fw-bold mb-1">Gestion des Agents</h1>
    <p class="text-secondary">Créez et gérez les comptes des agents pour l'accès API.</p>
</div>

<x-data-table :headers="['Nom & Prénom', 'Email', 'Téléphone', 'Date d\'ajout', 'Action']" :collection="$agents">
    <x-slot name="title">Liste des agents</x-slot>
    
    <x-slot name="actions">
        <button class="btn btn-sm btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#agentModal">
            <i class="fa-solid fa-user-plus me-2"></i> Nouveau Agent
        </button>
    </x-slot>

    @foreach($agents as $agent)
    <tr class="group">
        <td class="px-6 py-4">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-slate-100 text-slate-600 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: 600;">
                    {{ strtoupper(substr($agent->prenom, 0, 1)) }}{{ strtoupper(substr($agent->nom, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold text-dark">{{ $agent->prenom }} {{ $agent->nom }}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-dark">{{ $agent->email }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-secondary small">
                <i class="fa-solid fa-phone me-1 opacity-50"></i> {{ $agent->phone }}
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-secondary small">{{ $agent->created_at->format('d/m/Y') }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="dropdown">
                <button class="btn btn-sm btn-white border border-gray-100 text-secondary rounded-3 px-3 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="small fw-bold">Action</span>
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2">
                    <li>
                        <button class="dropdown-item small py-2 rounded-2 text-primary fw-bold mb-1" 
                                onclick="editAgent({{ json_encode($agent) }})"
                                data-bs-toggle="modal" data-bs-target="#agentModal">
                            <i class="fa-solid fa-pen-to-square me-2"></i> MODIFIER
                        </button>
                    </li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li>
                        <form action="{{ route('agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Supprimer cet agent ?')">
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

<!-- Agent Modal -->
<div class="modal fade" id="agentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-4 overflow-hidden">
            <div class="modal-header bg-slate-900 text-white border-0 py-4 px-6">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvel Agent</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="agentForm" method="POST" action="{{ route('agents.store') }}">
                @csrf
                <div id="method-field"></div>
                <div class="modal-body p-6">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-700">Nom</label>
                            <input type="text" name="nom" id="nom" class="form-control rounded-3 py-2" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-slate-700">Prénom</label>
                            <input type="text" name="prenom" id="prenom" class="form-control rounded-3 py-2" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-slate-700">Email (identifiant de connexion)</label>
                            <input type="email" name="email" id="email" class="form-control rounded-3 py-2" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-slate-700">Téléphone</label>
                            <input type="text" name="phone" id="phone" class="form-control rounded-3 py-2" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-slate-700">Mot de passe</label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 py-2" placeholder="Laisser vide pour ne pas modifier (en édition)">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 border-0 p-4">
                    <button type="button" class="btn btn-light px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-bold shadow-lg shadow-blue-200">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAgent(agent) {
    const form = document.getElementById('agentForm');
    form.action = `/agents/${agent.id}`;
    document.getElementById('modalTitle').innerText = 'Modifier l\'agent';
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    document.getElementById('nom').value = agent.nom;
    document.getElementById('prenom').value = agent.prenom;
    document.getElementById('email').value = agent.email;
    document.getElementById('phone').value = agent.phone;
    document.getElementById('password').required = false;
    document.getElementById('password').placeholder = "Laisser vide pour conserver l'actuel";
}

// Reset modal on close or "New Agent" click
document.getElementById('agentModal').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || event.relatedTarget.getAttribute('data-bs-target') === '#agentModal' && !event.relatedTarget.onclick) {
        const form = document.getElementById('agentForm');
        form.action = "{{ route('agents.store') }}";
        document.getElementById('modalTitle').innerText = 'Nouvel Agent';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('password').required = true;
        document.getElementById('password').placeholder = "";
        form.reset();
    }
});
</script>

<style>
    .bg-slate-100 { background-color: #f1f5f9; }
    .text-slate-600 { color: #475569; }
    .bg-slate-50 { background-color: #f8fafc; }
    .bg-slate-900 { background-color: #0f172a; }
    .bg-danger-subtle { background-color: #fef2f2 !important; }
</style>
@endsection
