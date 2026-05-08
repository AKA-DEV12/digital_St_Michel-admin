@extends('layouts.app')

@section('content')
    <div class="mb-5 animate-fade-in">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-3">
                <h1 class="h3 fw-bold mb-0" style="letter-spacing: -0.02em;">Tableau de bord</h1>
                <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill small fw-bold" style="font-size: 0.7rem;">VERSION PRO</span>
            </div>
            <div class="text-secondary small fw-bold">
                <i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>
        </div>
        <p class="text-secondary mb-0">Bienvenue, {{ Auth::user()->name }}. Voici l'état actuel de votre paroisse.</p>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5 animate-fade-in">
        @role('Super Admin')
        <!-- Revenue Card -->
        <div class="col-md-3">
            <div class="glass-card h-100 border-0 shadow-sm overflow-hidden position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fa-solid fa-money-bill-wave fs-1"></i>
                </div>
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fa-solid fa-coins text-success fs-4"></i>
                    </div>
                </div>
                <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Revenus Totaux</div>
                <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <span class="fs-6 fw-normal text-secondary">FCFA</span></div>
            </div>
        </div>
        @endrole

        <!-- Mass Requests Card -->
        <div class="{{ auth()->user()->hasRole('Super Admin') ? 'col-md-3' : 'col-md-4' }}">
            <div class="glass-card h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fa-solid fa-hands-praying text-primary fs-4"></i>
                    </div>
                    @if($stats['pending_mass_requests'] > 0)
                        <span class="badge bg-warning-light text-warning rounded-pill fw-bold" style="font-size: 0.7rem;">{{ $stats['pending_mass_requests'] }} Attente</span>
                    @endif
                </div>
                <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Demandes de Messe</div>
                <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_mass_requests']) }}</div>
            </div>
        </div>
        <!-- Reservations Card -->
        <div class="{{ auth()->user()->hasRole('Super Admin') ? 'col-md-3' : 'col-md-4' }}">
            <div class="glass-card h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="fa-solid fa-calendar-check text-info fs-4"></i>
                    </div>
                    @if($stats['pending_reservations'] > 0)
                        <span class="badge bg-warning-light text-warning rounded-pill fw-bold" style="font-size: 0.7rem;">{{ $stats['pending_reservations'] }} Attente</span>
                    @endif
                </div>
                <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Réservations Salles</div>
                <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_reservations']) }}</div>
            </div>
        </div>
        <!-- Registrations Card -->
        <div class="{{ auth()->user()->hasRole('Super Admin') ? 'col-md-3' : 'col-md-4' }}">
            <div class="glass-card h-100 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fa-solid fa-users text-warning fs-4"></i>
                    </div>
                </div>
                <div class="text-secondary small fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Inscriptions Public</div>
                <div class="h2 fw-bold mb-0 text-dark">{{ number_format($stats['total_registrations']) }}</div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row g-4 mb-5 animate-fade-in" style="animation-delay: 0.1s">
        <div class="col-md-3">
            <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm border">
                <div class="rounded-3 bg-danger bg-opacity-10 p-2">
                    <i class="fa-solid fa-users-viewfinder text-danger"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-bold" style="font-size: 0.6rem;">MEMBRES GROUPES</div>
                    <div class="fw-bold text-dark">{{ $stats['total_group_members'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm border">
                <div class="rounded-3 bg-primary bg-opacity-10 p-2">
                    <i class="fa-solid fa-user-tie text-primary"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-bold" style="font-size: 0.6rem;">CLERGÉ</div>
                    <div class="fw-bold text-dark">{{ $stats['total_priests'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm border">
                <div class="rounded-3 bg-success bg-opacity-10 p-2">
                    <i class="fa-solid fa-child-reaching text-success"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-bold" style="font-size: 0.6rem;">ENFANTS PAROISSE</div>
                    <div class="fw-bold text-dark">{{ number_format($stats['total_children']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="d-flex align-items-center gap-3 bg-white p-3 rounded-4 shadow-sm border">
                <div class="rounded-3 bg-info bg-opacity-10 p-2">
                    <i class="fa-solid fa-newspaper text-info"></i>
                </div>
                <div>
                    <div class="text-secondary small fw-bold" style="font-size: 0.6rem;">ARTICLES BLOG</div>
                    <div class="fw-bold text-dark">{{ $stats['total_posts'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4 animate-fade-in" style="animation-delay: 0.15s">
        <div class="col-md-6">
            <div class="glass-card h-100 border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-4 text-secondary small text-uppercase">État Matrimonial</h6>
                <div style="height: 300px;">
                    <canvas id="matrimonialChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="glass-card h-100 border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-4 text-secondary small text-uppercase">Répartition par Âge</h6>
                <div style="height: 300px;">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5 animate-fade-in" style="animation-delay: 0.18s">
        <div class="col-12">
            <div class="glass-card border-0 shadow-sm p-4">
                <h6 class="fw-bold mb-4 text-secondary small text-uppercase">Statistiques des Situations Professionnelles</h6>
                <div style="height: 350px;">
                    <canvas id="professionalChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 animate-fade-in" style="animation-delay: 0.2s">
        <div class="col-lg-8">
            <!-- Recent Reservations -->
            <div class="bg-white rounded-4 shadow-sm border p-0 overflow-hidden mb-4">
                <div class="p-4 d-flex justify-content-between align-items-center border-bottom bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-calendar-check text-primary"></i>
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">Dernières Réservations</h5>
                    </div>
                    <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-white border rounded-pill px-3 fw-bold small">Voir tout</a>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="border-0 px-4 py-3">Client</th>
                                    <th class="border-0 py-3">Salle</th>
                                    <th class="border-0 py-3">Date</th>
                                    <th class="border-0 px-4 py-3 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_reservations as $res)
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-bold text-dark">{{ $res->first_name }} {{ $res->last_name }}</div>
                                        <div class="small text-secondary">{{ $res->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-secondary border-0 px-2 py-1 small fw-bold">{{ $res->room->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($res->reservation_date)->format('d/m/Y') }}</div>
                                        <div class="small text-secondary">{{ $res->time_slots_display ?? $res->time_slot }}</div>
                                    </td>
                                    <td class="px-4 text-end">
                                        @if($res->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">En attente</span>
                                        @elseif($res->status == 'validated')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Validée</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Annulée</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">Aucune réservation récente</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Mass Requests -->
            <div class="bg-white rounded-4 shadow-sm border p-0 overflow-hidden">
                <div class="p-4 d-flex justify-content-between align-items-center border-bottom bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-hands-praying text-primary"></i>
                        <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">Demandes de Messe Récentes</h5>
                    </div>
                    <a href="{{ route('admin.mass_requests.index') }}" class="btn btn-sm btn-white border rounded-pill px-3 fw-bold small">Voir tout</a>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                            <thead class="bg-light bg-opacity-50">
                                <tr>
                                    <th class="border-0 px-4 py-3">Demandeur</th>
                                    <th class="border-0 py-3">Objet</th>
                                    <th class="border-0 py-3">Montant</th>
                                    <th class="border-0 px-4 py-3 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_mass_requests as $request)
                                <tr>
                                    <td class="px-4">
                                        <div class="fw-bold text-dark">{{ $request->name1 }}</div>
                                        <div class="small text-secondary">{{ $request->phone }}</div>
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;">
                                        {{ $request->request_object }}
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ number_format($request->amount, 0, ',', ' ') }} FCFA</div>
                                    </td>
                                    <td class="px-4 text-end">
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">En attente</span>
                                        @elseif($request->status == 'confirmed')
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Payé</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Annulé</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary">Aucune demande de messe</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-4 shadow-sm border p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0" style="font-size: 1.1rem;">À venir (RDV)</h5>
                    <a href="{{ route('admin.priest_appointments.index') }}" class="small text-primary fw-bold text-decoration-none">Voir tout</a>
                </div>
                <div class="d-grid gap-3">
                    @forelse($upcoming_appointments as $app)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-4 border bg-light bg-opacity-10 hover-lift">
                        <div class="rounded-circle bg-white shadow-sm p-1" style="width: 45px; height: 45px; flex-shrink: 0;">
                            <div class="rounded-circle bg-primary bg-opacity-10 h-100 w-100 d-flex align-items-center justify-content-center">
                                <i class="fa-solid fa-user text-primary" style="font-size: 0.8rem;"></i>
                            </div>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark text-truncate">{{ $app->full_name }}</div>
                            <div class="small text-secondary text-truncate">avec {{ $app->priest->name }}</div>
                            <div class="small mt-1 text-primary fw-bold">
                                <i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($app->appointment_date)->format('d M') }} à {{ $app->time_slot }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-secondary small">Aucun rendez-vous à venir</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-4 shadow-sm border p-4 mb-4">
                <h5 class="fw-bold mb-4" style="font-size: 1.1rem;">Actions Rapides</h5>
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.mass_requests.index') }}" class="btn btn-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                        <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-3">
                            <i class="fa-solid fa-hands-praying text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-0">Demandes de Messe</div>
                            <div class="small text-secondary">Gérer les intentions</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.catechists.create') }}" class="btn btn-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                        <div class="rounded-3 bg-danger bg-opacity-10 p-2 me-3">
                            <i class="fa-solid fa-user-plus text-danger"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-0">Nouveau Catéchiste</div>
                            <div class="small text-secondary">Inscription base de données</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-light border text-start p-3 rounded-4 hover-lift d-flex align-items-center">
                        <div class="rounded-3 bg-info bg-opacity-10 p-2 me-3">
                            <i class="fa-solid fa-pen-nib text-info"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark mb-0">Publier un article</div>
                            <div class="small text-secondary">Actualités de la paroisse</div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="rounded-4 p-4 text-white border-0 shadow-lg" style="background: linear-gradient(135deg, #dc143c 0%, #9f1239 100%) !important;">
                <div class="position-relative z-index-1">
                    <h6 class="fw-bold mb-3">Guide de Gestion</h6>
                    <p class="small opacity-90 mb-4">Besoin d'aide pour gérer les réservations ou les demandes de messe ?</p>
                    <a href="{{ route('admin.documentation') }}" class="btn btn-white btn-sm px-4 rounded-pill fw-bold text-primary" style="background: white !important; border: none !important;">Voir la documentation</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-primary-light { background-color: #fff1f2 !important; }
        .bg-success-light { background-color: #ecfdf5 !important; }
        .bg-warning-light { background-color: #fffbeb !important; }
        .hover-lift { transition: all 0.2s ease; border: 1px solid #eef2f6 !important; }
        .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); border-color: var(--primary) !important; background: white !important; }
        .btn-white { background: white !important; color: var(--secondary) !important; }
        .btn-white:hover { border-color: var(--primary) !important; color: var(--primary) !important; }
        .text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart configuration defaults
            Chart.defaults.font.family = "'Outfit', sans-serif";
            Chart.defaults.color = '#64748b';

            // Matrimonial Chart
            const matCtx = document.getElementById('matrimonialChart').getContext('2d');
            new Chart(matCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($matrimonial_stats->pluck('situation_matrimoniale')) !!},
                    datasets: [{
                        data: {!! json_encode($matrimonial_stats->pluck('total')) !!},
                        backgroundColor: ['#dc143c', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                    },
                    cutout: '70%'
                }
            });

            // Age Chart
            const ageCtx = document.getElementById('ageChart').getContext('2d');
            new Chart(ageCtx, {
                type: 'polarArea',
                data: {
                    labels: ['Enfants (<15)', 'Jeunes (15-35)', 'Adultes (>35)'],
                    datasets: [{
                        data: [{{ $age_stats['enfants'] }}, {{ $age_stats['jeunes'] }}, {{ $age_stats['adultes'] }}],
                        backgroundColor: [
                            'rgba(220, 20, 60, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(59, 130, 246, 0.7)'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: { ticks: { display: false }, grid: { color: 'rgba(0,0,0,0.05)' } }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                    }
                }
            });

            // Professional Chart
            const profCtx = document.getElementById('professionalChart').getContext('2d');
            new Chart(profCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($professional_stats->pluck('situation_professionnelle')) !!},
                    datasets: [{
                        label: 'Membres',
                        data: {!! json_encode($professional_stats->pluck('total')) !!},
                        borderColor: '#dc143c',
                        backgroundColor: 'rgba(220, 20, 60, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#dc143c',
                        pointRadius: 5,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { stepSize: 1 }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1e293b',
                            bodyColor: '#64748b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            displayColors: false
                        }
                    }
                }
            });
        });
    </script>
    @endpush
@endsection

