@extends('layouts.app')

@section('content')
<div class="mb-4 animate-fade-in">
    <div class="d-flex align-items-center gap-3 mb-3">
        <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="h3 fw-bold mb-0">Détails de l'inscription</h1>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Info Card -->
    <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.1s">
        <div class="glass-card text-center py-5">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem; font-weight: 700;">
                {{ strtoupper(substr($registration->option === 'Individuel' ? $registration->full_name : ($registration->group_name ?? 'G'), 0, 1)) }}
            </div>
            <h4 class="fw-bold mb-1">
                {{ $registration->option === 'Individuel' ? $registration->full_name : ($registration->group_name ?? 'Groupe non nommé') }}
            </h4>
            <p class="text-secondary mb-4">{{ $registration->phone_number }}</p>
            
            <div class="d-flex justify-content-center gap-3 mb-4">
                @if($registration->status == 'pending')
                    <span class="badge-pill bg-warning bg-opacity-10 text-warning px-4 py-2">En attente</span>
                @elseif($registration->status == 'confirmed')
                    <span class="badge-pill bg-success bg-opacity-10 text-success px-4 py-2">Confirmée</span>
                @else
                    <span class="badge-pill bg-danger bg-opacity-10 text-danger px-4 py-2">Annulée</span>
                @endif
            </div>

            <hr class="my-4 opacity-50">

            <div class="space-y-4 text-start px-3">
                <div class="mb-3">
                    <label class="small text-secondary text-uppercase fw-bold">Activité</label>
                    <div class="fw-bold">{{ $registration->registrationActivity->title ?? 'N/A' }}</div>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary text-uppercase fw-bold">Date de l'activité</label>
                    <div class="fw-bold">{{ \Carbon\Carbon::parse($registration->registrationActivity->date)->format('d/m/Y') }}</div>
                </div>
                <div class="mb-0">
                    <label class="small text-secondary text-uppercase fw-bold">Montant Total</label>
                    <div class="h4 fw-bold text-primary">{{ number_format($participants->sum('amount'), 0, ',', ' ') }} FCFA</div>
                </div>
            </div>
        </div>

        @if($registration->status === 'pending')
        <!-- Action Card -->
        <div class="glass-card">
            <h5 class="fw-bold mb-4">Changer le statut</h5>
            <form action="{{ route('admin.registrations.update_status', $registration->uuid) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <select name="status" class="form-select rounded-3">
                        <option value="confirmed">Confirmer</option>
                        <option value="cancelled">Annuler</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill">Mettre à jour</button>
            </form>
        </div>
        @else
        <div class="glass-card bg-light border-0">
            <div class="text-center py-2">
                <i class="fa-solid fa-lock text-secondary mb-2"></i>
                <p class="small text-secondary mb-0">Statut verrouillé</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Tabs Card -->
    <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s">
        <div class="glass-card h-100">
            <!-- Nav Tabs -->
            <ul class="nav nav-pills mb-4 gap-2" id="registrationTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4" id="participants-tab" data-bs-toggle="pill" data-bs-target="#participantsList" type="button" role="tab" aria-controls="participantsList" aria-selected="true">
                        <i class="fa-solid fa-users me-2"></i> Participants ({{ $participants->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="details-tab" data-bs-toggle="pill" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false">
                        <i class="fa-solid fa-list-check me-2"></i> Informations
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="false">
                        <i class="fa-solid fa-receipt me-2"></i> Preuve de Paiement
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="registrationTabContent">
                <!-- Participants List Tab -->
                <div class="tab-pane fade show active" id="participantsList" role="tabpanel" aria-labelledby="participants-tab">
                    <div class="space-y-3">
                        @foreach($participants as $participant)
                        <div class="p-3 bg-light rounded-4 d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; color: var(--primary);">
                                    <i class="fa-solid fa-user small"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $participant->full_name }}</div>
                                    <div class="small text-secondary">{{ $participant->phone_number }}</div>
                                </div>
                            </div>
                            <div class="fw-bold text-primary">
                                {{ number_format($participant->amount, 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Details Tab -->
                <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">Option choisie</label>
                                <div class="h5 fw-bold mb-0">{{ $registration->option }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">Nom du Groupe</label>
                                <div class="h5 fw-bold mb-0">{{ $registration->group_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-4 bg-light rounded-4">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">UUID de l'inscription</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="h5 fw-bold mb-0 font-monospace text-primary text-break">{{ $registration->uuid }}</div>
                                    <button class="btn btn-sm btn-outline-secondary rounded-circle p-2" onclick="navigator.clipboard.writeText('{{ $registration->uuid }}')">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">Email de paiement</label>
                                <div class="fw-bold">{{ $registration->payment_email ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-4">
                                <label class="small text-secondary text-uppercase fw-bold mb-1">Opérateur</label>
                                <div class="fw-bold">{{ $registration->payment_operator ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Receipt Tab -->
                <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                    <div class="text-center p-4">
                        @if($registration->payment_receipt)
                            <div class="mb-4">
                                <label class="small text-secondary text-uppercase fw-bold d-block mb-3">Reçu de paiement</label>
                                <div class="bg-light rounded-4 p-3 border">
                                    @php
                                        $isImage = in_array(pathinfo($registration->payment_receipt, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']);
                                    @endphp
                                    
                                    @if($isImage)
                                        <img src="{{ asset('storage/' . $registration->payment_receipt) }}" alt="Reçu" class="img-fluid rounded-3 shadow-sm mx-auto d-block" style="max-height: 500px">
                                    @else
                                        <div class="py-5">
                                            <i class="fa-solid fa-file-pdf fs-1 text-danger mb-3"></i>
                                            <div class="fw-bold">Document PDF</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ asset('storage/' . $registration->payment_receipt) }}" target="_blank" class="btn btn-primary rounded-pill px-4">
                                    <i class="fa-solid fa-expand me-2"></i> Voir en plein écran
                                </a>
                                <a href="{{ asset('storage/' . $registration->payment_receipt) }}" download class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fa-solid fa-download me-2"></i> Télécharger
                                </a>
                            </div>
                        @else
                            <div class="py-5 text-secondary">
                                <i class="fa-solid fa-file-circle-xmark fs-1 mb-3 opacity-25"></i>
                                <div class="fw-bold">Aucun reçu n'a été téléchargé pour cette inscription.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
