@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex align-items-center gap-3 mb-2">
        <h1 class="h3 fw-bold mb-0" style="letter-spacing: -0.02em;">Vue d'ensemble</h1>
        <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill small fw-bold" style="font-size: 0.7rem;">EN DIRECT</span>
    </div>
    <p class="text-secondary mb-0">Bienvenue, {{ Auth::user()->name }}. Voici l'activité de votre plateforme aujourd'hui.</p>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-5 animate-fade-in">
    <div class="col-md-3">
        <div class="glass-card h-100 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="fa-solid fa-calendar-check text-primary fs-4"></i>
                </div>
                <span class="badge bg-success-light text-success rounded-pill fw-bold" style="font-size: 0.7rem;">+12%</span>
            </div>
            <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Réservations</div>
            <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_reservations']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card h-100 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="fa-solid fa-users text-success fs-4"></i>
                </div>
                <span class="badge bg-primary-light text-primary rounded-pill fw-bold" style="font-size: 0.7rem;">Nouveau</span>
            </div>
            <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Inscriptions</div>
            <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_registrations']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card h-100 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="fa-solid fa-newspaper text-info fs-4"></i>
                </div>
            </div>
            <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Articles Blog</div>
            <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_posts']) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-card h-100 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="fa-solid fa-user-tie text-warning fs-4"></i>
                </div>
            </div>
            <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Pères</div>
            <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_priests']) }}</div>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in" style="animation-delay: 0.2s">
    <div class="col-lg-8">
        <div class="bg-white rounded-4 shadow-sm border p-0 overflow-hidden">
            <div class="p-4 d-flex justify-content-between align-items-center border-bottom bg-light bg-opacity-50">
                <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">Dernières Réservations</h5>
                <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-white border rounded-pill px-3 fw-bold small">Voir tout</a>
            </div>
            <div class="p-4">
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Client / Email</th>
                                <th>Salle</th>
                                <th>Date & Heure</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_reservations as $res)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3" style="width: 32px; height: 32px; font-size: 0.8rem; flex-shrink: 0;">
                                            {{ strtoupper(substr($res->first_name, 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ $res->first_name }} {{ $res->last_name }}</div>
                                            <div class="small text-secondary text-truncate">{{ $res->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border-0 px-2 py-1 small fw-bold">{{ $res->room->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d M Y') }}</div>
                                    <div class="small text-secondary">{{ $res->time_slot }}</div>
                                </td>
                                <td>
                                    @if($res->status == 'pending')
                                        <span class="badge-pill bg-warning bg-opacity-10 text-warning">En attente</span>
                                    @elseif($res->status == 'validated')
                                        <span class="badge-pill bg-success bg-opacity-10 text-success">Validée</span>
                                    @else
                                        <span class="badge-pill bg-danger bg-opacity-10 text-danger">Annulée</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-folder-open fs-2 mb-3 d-block opacity-20"></i>
                                    Aucune donnée trouvée
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bg-white rounded-4 shadow-sm border p-4 mb-4">
            <h5 class="fw-bold mb-4" style="font-size: 1.1rem;">Actions Rapides</h5>
            <div class="d-grid gap-3">
                <a href="{{ route('admin.blog.index') }}" class="btn btn-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-3">
                        <i class="fa-solid fa-pen-nib text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0">Publier un article</div>
                        <div class="small text-secondary">Gérer le blog premium</div>
                    </div>
                </a>
                <a href="{{ route('reservations.index') }}" class="btn btn-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-2 me-3">
                        <i class="fa-solid fa-calendar-plus text-success"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark mb-0">Gérer les réservations</div>
                        <div class="small text-secondary">Planning des salles</div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="rounded-4 p-4 text-white border-0 shadow-lg" style="background: linear-gradient(135deg, #dc143c 0%, #9f1239 100%) !important;">
            <div class="position-relative z-index-1">
                <h6 class="fw-bold mb-3">Besoin d'aide ?</h6>
                <p class="small opacity-90 mb-4">Consultez notre documentation ou contactez l'assistance technique pour toute question sur le dashboard.</p>
                <a href="#" class="btn btn-white btn-sm px-4 rounded-pill fw-bold text-primary" style="background: white !important; border: none !important;">Contacter le support</a>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-light { background-color: #fff1f2 !important; }
    .bg-success-light { background-color: #ecfdf5 !important; }
    .hover-lift { transition: all 0.2s ease; border: 1px solid #eef2f6 !important; }
    .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); border-color: var(--primary) !important; background: white !important; }
    .btn-white { background: white !important; color: var(--secondary) !important; }
    .btn-white:hover { border-color: var(--primary) !important; color: var(--primary) !important; }
</style>
@endsection

