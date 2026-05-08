@extends('layouts.app')

@section('content')
<div class="mb-4 animate-fade-in">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('admin.mass_requests.index') }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="h3 fw-bold mb-0">Configuration Demande de Messe</h1>
    </div>
</div>

<div class="row g-4">
    <!-- Settings Form -->
    <div class="col-lg-7 animate-fade-in" style="animation-delay: 0.1s">
        <div class="glass-card">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-sliders me-2 text-primary"></i> Paramètres Généraux</h5>
            
            <form action="{{ route('admin.mass_requests.update_settings') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Montant par intention (FCFA)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fa-solid fa-money-bill text-primary"></i></span>
                        <input type="number" name="mass_price" class="form-control border-start-0 rounded-end-3 py-2" value="{{ $settings['mass_price'] }}" required>
                    </div>
                    <div class="form-text">Ce montant sera multiplié par le nombre de créneaux choisis par l'utilisateur.</div>
                </div>

                <h6 class="fw-bold mb-3 mt-5">Numéros de Paiement</h6>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Lien de paiement Wave</label>
                        <input type="text" name="wave_number" class="form-control rounded-3" value="{{ $settings['wave_number'] }}" placeholder="https://wave.com/...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Numéro MTN MoMo</label>
                        <input type="text" name="mtn_number" class="form-control rounded-3" value="{{ $settings['mtn_number'] }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Numéro Orange Money</label>
                        <input type="text" name="orange_number" class="form-control rounded-3" value="{{ $settings['orange_number'] }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-secondary fw-bold text-uppercase">Numéro Moov Money</label>
                        <input type="text" name="moov_number" class="form-control rounded-3" value="{{ $settings['moov_number'] }}">
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-save me-2"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hours Management -->
    <div class="col-lg-5 animate-fade-in" style="animation-delay: 0.2s">
        <div class="glass-card">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-clock me-2 text-primary"></i> Créneaux Horaires</h5>
            
            <form action="{{ route('admin.mass_requests.store_time') }}" method="POST" class="mb-4">
                @csrf
                <div class="input-group">
                    <input type="time" name="time" class="form-control rounded-start-3" required>
                    <button type="submit" class="btn btn-primary rounded-end-3 px-4">
                        <i class="fa-solid fa-plus"></i> Ajouter
                    </button>
                </div>
            </form>

            <div class="list-group list-group-flush border-top">
                @forelse($times as $time)
                <div class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="fa-regular fa-clock small"></i>
                        </div>
                        <span class="fw-bold h5 mb-0">{{ $time->time }}</span>
                    </div>
                    <form action="{{ route('admin.mass_requests.destroy_time', $time->id) }}" method="POST" onsubmit="return confirm('Supprimer ce créneau ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0 border-0">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
                @empty
                <div class="text-center py-5 text-secondary">
                    <i class="fa-solid fa-calendar-xmark fs-2 mb-3 opacity-25"></i>
                    <p class="mb-0">Aucun créneau configuré.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
