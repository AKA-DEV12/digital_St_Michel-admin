@extends('layouts.app')

@section('content')
<div class="mb-4 animate-fade-in">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('admin.participant_groups.index') }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="h3 fw-bold mb-0">Détails du Groupe : {{ $group->name }}</h1>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Info Card -->
    <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.1s">
        <div class="glass-card text-center py-5">
            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                <i class="fa-solid fa-users"></i>
            </div>
            <h4 class="fw-bold mb-1">
                {{ $group->name }}
            </h4>
            <p class="text-secondary mb-4">Groupe n° {{ $group->id }}</p>
            
            <div class="d-flex justify-content-center gap-3 mb-4">
                <span class="badge-pill bg-success bg-opacity-10 text-success px-4 py-2 border-0 shadow-sm fw-bold">Constitué / Complet</span>
            </div>

            <hr class="my-4 opacity-50">

            <div class="space-y-4 text-start px-3">
                <div class="mb-3">
                    <label class="small text-secondary text-uppercase fw-bold">Taille Cible</label>
                    <div class="fw-bold">{{ $group->target_size }} personnes</div>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary text-uppercase fw-bold">Inscriptions Associées (Dossiers)</label>
                    <div class="fw-bold">{{ $group->registrations->groupBy('uuid')->count() }}</div>
                </div>
                <div class="mb-0">
                    <label class="small text-secondary text-uppercase fw-bold">Total Individus Compilés</label>
                    <div class="h4 fw-bold text-primary">{{ $group->registrations->count() }} personnes</div>
                </div>
                <div class="mt-3">
                    <label class="small text-secondary text-uppercase fw-bold">Date de Création</label>
                    <div class="fw-bold small">{{ $group->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: List of all individuals in the group -->
    <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s">
        <div class="glass-card h-100">
            
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-list-check me-2 text-secondary"></i> Membres du Groupe ({{ $group->registrations->count() }})</h5>

            <div class="space-y-3" style="max-height: 600px; overflow-y: auto;">
                @php
                    $groupedRegistrations = $group->registrations->groupBy(function($reg) {
                        return $reg->group_name ?: ($reg->option === 'Individuel' ? 'Inscriptions Individuelles' : 'Inscriptions Multiples (Sans nom)');
                    })->sortKeys();
                @endphp

                @foreach($groupedRegistrations as $groupName => $participants)
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3 position-sticky top-0 bg-white py-2 z-1" style="border-bottom: 2px solid #f8fafc;">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-circle fw-bold d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 35px; height: 35px; font-size: 1rem;">
                                {{ strtoupper(substr($groupName, 0, 1)) }}
                            </span>
                            <h6 class="fw-bold text-secondary mb-0 text-uppercase" style="letter-spacing: 0.5px;">{{ $groupName }} <span class="badge bg-slate-200 text-slate-700 ms-2 rounded-pill">{{ $participants->count() }}</span></h6>
                        </div>

                        <div class="space-y-2 ps-2 border-start border-2 border-slate-100 ms-3">
                            @foreach($participants as $participant)
                            <div class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center mb-2 shadow-sm border border-light hover-bg-slate-50 ms-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; color: var(--primary);">
                                        <i class="fa-solid fa-user small"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $participant->full_name }}</div>
                                        <div class="small text-secondary d-flex align-items-center gap-2 mt-1">
                                            <span><i class="fa-solid fa-phone me-1"></i> {{ $participant->phone_number }}</span>
                                            <span class="text-slate-300">|</span>
                                            <span><i class="fa-solid fa-folder me-1"></i> UUID: {{ substr($participant->uuid, 0, 8) }}...</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <div class="fw-bold text-primary mb-1">
                                        {{ number_format($participant->amount, 0, ',', ' ') }} FCFA
                                    </div>
                                    <span class="badge bg-slate-200 text-slate-700 rounded-pill px-2 py-1" style="font-size: 0.70rem;">
                                        {{ $participant->option }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                @if($group->registrations->isEmpty())
                <div class="py-5 text-center text-secondary">
                    <i class="fa-solid fa-user-slash fs-1 mb-3 opacity-25"></i>
                    <p class="mb-0">Ce groupe semble vide. Il ne contient aucune inscription.</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    .bg-slate-200 { background-color: #e2e8f0; }
    .text-slate-300 { color: #cbd5e1; }
    .text-slate-700 { color: #334155; }
    .hover-bg-slate-50:hover { background-color: #f8fafc; transition: 0.2s; }
</style>
@endsection
