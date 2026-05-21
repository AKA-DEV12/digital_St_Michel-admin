@extends('layouts.app')

@section('content')
    <div class="mb-4 animate-fade-in">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('reservations.index', ['status' => $reservation->status]) }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="h3 fw-bold mb-0">Détails de la réservation</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Info Card -->
        <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.1s">
            <div class="glass-card text-center py-5">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem; font-weight: 700;">
                    {{ strtoupper(substr($reservation->first_name, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $reservation->first_name }} {{ $reservation->last_name }}</h4>
                <p class="text-secondary mb-4">{{ $reservation->phone }}</p>

                <div class="d-flex justify-content-center gap-3 mb-4">
                    @if($reservation->status == 'pending')
                        <span class="badge-pill bg-warning bg-opacity-10 text-warning px-4 py-2">En attente</span>
                    @elseif($reservation->status == 'validated')
                        <span class="badge-pill bg-success bg-opacity-10 text-success px-4 py-2">Validée</span>
                    @else
                        <span class="badge-pill bg-danger bg-opacity-10 text-danger px-4 py-2">Annulée</span>
                    @endif
                </div>

                <hr class="my-4 opacity-50">

                <div class="space-y-4 text-start px-3">
                    <div class="mb-3">
                        <label class="small text-secondary text-uppercase fw-bold">Date de réservation</label>
                        <div class="fw-bold">{{ $reservation->reservation_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-secondary text-uppercase fw-bold">Salle</label>
                        <div class="fw-bold">{{ $reservation->room->name ?? 'Non spécifiée' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-secondary text-uppercase fw-bold">Créneaux</label>
                        <div class="fw-bold">{{ $reservation->time_slots_display }}</div>
                    </div>
                    <div class="mb-0">
                        <label class="small text-secondary text-uppercase fw-bold">Montant Total</label>
                        <div class="h4 fw-bold text-primary">{{ number_format($reservation->price, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>

                
                 @if($reservation->status === 'pending')
                <div class="d-grid gap-2 mt-7">
                    <form action="{{ route('reservations.validate', $reservation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 rounded-pill mb-2">
                            <i class="fa-solid fa-check me-2"></i> Valider et envoyer mail
                        </button>
                    </form>
                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                            <i class="fa-solid fa-xmark me-2"></i> Annuler la réservation
                        </button>
                    </form>
                </div>
                @endif
            </div>

        </div>

        <!-- Right Column: Tabs Card -->
        <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s">
            <div class="glass-card h-100">
                <ul class="nav nav-pills custom-red-tabs mb-4 gap-2" id="reservationTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4" id="details-tab" data-bs-toggle="pill" data-bs-target="#details" type="button" role="tab">
                            <i class="fa-solid fa-info-circle me-2"></i> Détails
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab">
                            <i class="fa-solid fa-receipt me-2"></i> Preuve de Paiement
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="reservationTabContent">
                    <div class="tab-pane fade show active" id="details" role="tabpanel">
                        <div class="p-4 bg-light rounded-4 mb-4">
                            <label class="small text-secondary text-uppercase fw-bold mb-2">Objet de la réservation</label>
                            <div class="h5 fw-bold">{{ $reservation->reservation_object }}</div>
                        </div>

                        <h6 class="fw-bold mb-3 px-1">Informations de contact</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-white border rounded-4">
                                    <label class="small text-secondary text-uppercase fw-bold mb-1">Email</label>
                                    <div class="fw-bold">{{ $reservation->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white border rounded-4">
                                    <label class="small text-secondary text-uppercase fw-bold mb-1">Téléphone</label>
                                    <div class="fw-bold">{{ $reservation->phone }}</div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 px-1">Détails de la réservation</h6>
                        <div class="space-y-2">
                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="fa-solid fa-door-open"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Salle</div>
                                    <div class="text-secondary small">{{ $reservation->room->name ?? 'Non spécifiée' }}</div>
                                </div>
                            </div>
                            
                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="fa-solid fa-calendar"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Date</div>
                                    <div class="text-secondary small">{{ $reservation->reservation_date->format('d/m/Y') }}</div>
                                </div>
                            </div>

                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Créneaux horaires</div>
                                    <div class="text-secondary small">{{ $reservation->time_slots_display }}</div>
                                </div>
                            </div>

                            @if($reservation->group_name)
                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Groupe</div>
                                    <div class="text-secondary small">{{ $reservation->group_name }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="small text-secondary text-uppercase fw-bold mb-1">Email de paiement</label>
                                    <div class="fw-bold">{{ $reservation->payment_email ?: $reservation->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="small text-secondary text-uppercase fw-bold mb-1">Opérateur</label>
                                    <div class="fw-bold">{{ $reservation->payment_operator ?: 'Non spécifié' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center p-4">
                            @if($reservation->payment_receipt)
                                @php
                                    $receiptUrl = $reservation->payment_receipt_url;
                                    $isImage = $receiptUrl && in_array(pathinfo($receiptUrl, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']);
                                @endphp
                                <div class="bg-light rounded-4 p-3 border mb-4">
                                    @if($isImage)
                                        <img src="{{ $receiptUrl }}" alt="Reçu" class="img-fluid rounded-3 shadow-sm mx-auto d-block" style="max-height: 500px">
                                    @else
                                        <div class="py-5">
                                            <i class="fa-solid fa-file-pdf fs-1 text-danger mb-3"></i>
                                            <div class="fw-bold">Document PDF</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ $receiptUrl }}" target="_blank" class="btn btn-primary rounded-pill px-4">Voir</a>
                                    <a href="{{ $receiptUrl }}" download class="btn btn-outline-secondary rounded-pill px-4">Télécharger</a>
                                </div>
                            @else
                                <div class="py-5 text-secondary">
                                    <i class="fa-solid fa-file-circle-xmark fs-1 mb-3 opacity-25"></i>
                                    <div class="fw-bold">Aucun reçu n'a été téléchargé.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
