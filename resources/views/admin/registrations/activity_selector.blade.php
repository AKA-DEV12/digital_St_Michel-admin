@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="text-center mb-5">
        <h1 class="h2 fw-bold text-dark mb-2">Portail de Gestion</h1>
        <p class="text-secondary fs-5">Veuillez sélectionner une activité pour accéder à sa gestion dédiée.</p>
    </div>

    <div class="d-flex flex-column gap-4">
        @forelse($activities as $index => $item)
        @php
            $route = 'admin.registrations.index';
            if ($target === 'scanned') $route = 'admin.registrations.scanned';
            if ($target === 'groups') $route = 'admin.participant_groups.index';
        @endphp
        <div class="glass-card p-4 animate-fade-in" style="animation-delay: {{ 0.1 + ($index * 0.1) }}s; border-radius: 30px;">
            <div class="row align-items-center g-4">
                <!-- Icon column -->
                <div class="col-auto">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: transparent;">
                        <i class="fa-solid fa-person-walking fs-2 text-primary"></i>
                    </div>
                </div>

                <!-- Content column -->
                <div class="col">
                    <div class="mb-3">
                        <h3 class="h4 fw-bold text-dark mb-1 text-uppercase">{{ $item->title }}</h3>
                        <p class="text-secondary mb-0 fw-medium">{{ $item->subtitle }}</p>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <div class="badge-pill-light d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background-color: #f1f5f9; color: #475569; font-size: 0.85rem;">
                            <i class="fa-solid fa-calendar text-danger"></i> 
                            {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                        </div>
                        <div class="badge-pill-light d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background-color: #f1f5f9; color: #475569; font-size: 0.85rem;">
                            <i class="fa-solid fa-clock text-danger"></i> 
                            {{ $item->start_time ? substr($item->start_time, 0, 5) : '00:00' }} - {{ $item->end_time ? substr($item->end_time, 0, 5) : '00:00' }}
                        </div>
                        <div class="badge-pill-light d-flex align-items-center gap-2 px-3 py-2 rounded-pill" style="background-color: #f1f5f9; color: #475569; font-size: 0.85rem;">
                            <i class="fa-solid fa-location-dot text-danger"></i> 
                            {{ $item->location ?? 'Non spécifié' }}
                        </div>
                    </div>
                </div>

                <!-- Action column -->
                <div class="col-auto ms-auto pe-4">
                    <a href="{{ route($route, ['activity_id' => $item->id]) }}" 
                       class="btn btn-crimson rounded-4 px-5 py-3 fw-bold d-flex align-items-center gap-3 text-white shadow-lg"
                       style="background-color: #e11d48; min-width: 180px;">
                        Accéder
                        <i class="fa-solid fa-chevron-right small"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="p-5 text-center glass-card border-dashed" style="border-radius: 30px;">
            <div class="w-20 h-20 bg-slate-50 rounded-full d-flex align-items-center justify-content-center mx-auto mb-4">
                <i class="fa-solid fa-calendar-xmark text-slate-300 fs-1"></i>
            </div>
            <h3 class="h4 fw-bold text-dark mb-2">Aucune activité configurée</h3>
            <p class="text-secondary">Créez d'abord une activité pour pouvoir gérer ses inscriptions.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    .btn-crimson {
        background-color: #e11d48 !important; /* Crimson red like the screenshot */
        border: none;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
    }
    .btn-crimson:hover {
        background-color: #be123c !important;
        transform: scale(1.02);
        box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.4);
    }
    .glass-card {
        background: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px -10px rgba(0,0,0,0.08);
    }
</style>
@endsection
