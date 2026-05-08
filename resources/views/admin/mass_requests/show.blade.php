@extends('layouts.app')

@section('content')
    <div class="mb-4 animate-fade-in">
        <div class="d-flex align-items-center gap-3 mb-3">
            <a href="{{ route('admin.mass_requests.index') }}" class="btn btn-outline-secondary rounded-circle p-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="h3 fw-bold mb-0">Détails de la demande de messe</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Info Card -->
        <div class="col-lg-4 animate-fade-in" style="animation-delay: 0.1s">
            <div class="glass-card text-center py-5">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem; font-weight: 700;">
                    {{ strtoupper(substr($request->name1, 0, 1)) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $request->name1 }}</h4>
                <p class="text-secondary mb-4">{{ $request->phone }}</p>

                <div class="d-flex justify-content-center gap-3 mb-4">
                    @if($request->status == 'pending')
                        <span class="badge-pill bg-warning bg-opacity-10 text-warning px-4 py-2">En attente</span>
                    @elseif($request->status == 'confirmed')
                        <span class="badge-pill bg-success bg-opacity-10 text-success px-4 py-2">Confirmée</span>
                    @else
                        <span class="badge-pill bg-danger bg-opacity-10 text-danger px-4 py-2">Annulée</span>
                    @endif
                </div>

                <hr class="my-4 opacity-50">

                <div class="space-y-4 text-start px-3">
                    <div class="mb-3">
                        <label class="small text-secondary text-uppercase fw-bold">Date de la Messe</label>
                        <div class="fw-bold">{{ $request->requested_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-secondary text-uppercase fw-bold">Nombre d'intentions</label>
                        <div class="fw-bold">{{ count($request->time_slots) }} créneau(x)</div>
                    </div>
                    <div class="mb-0">
                        <label class="small text-secondary text-uppercase fw-bold">Montant Total</label>
                        <div class="h4 fw-bold text-primary">{{ number_format($request->amount, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>

                
                 @if($request->status === 'pending')
                <div class="d-grid gap-2 mt-7">
                    <form action="{{ route('admin.mass_requests.validate', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 rounded-pill mb-2">
                            <i class="fa-solid fa-check me-2"></i> Valider et envoyer mail
                        </button>
                    </form>
                    <form action="{{ route('admin.mass_requests.cancel', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill">
                            <i class="fa-solid fa-xmark me-2"></i> Annuler la demande
                        </button>
                    </form>
                </div>
                @endif
            </div>

        </div>

        <!-- Right Column: Tabs Card -->
        <div class="col-lg-8 animate-fade-in" style="animation-delay: 0.2s">
            <div class="glass-card h-100">
                <ul class="nav nav-pills custom-red-tabs mb-4 gap-2" id="massTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-4" id="intentions-tab" data-bs-toggle="pill" data-bs-target="#intentionsList" type="button" role="tab">
                            <i class="fa-solid fa-hands-praying me-2"></i> Intentions
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-4" id="payment-tab" data-bs-toggle="pill" data-bs-target="#payment" type="button" role="tab">
                            <i class="fa-solid fa-receipt me-2"></i> Preuve de Paiement
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="massTabContent">
                    <div class="tab-pane fade show active" id="intentionsList" role="tabpanel">
                        <div class="p-4 bg-light rounded-4 mb-4">
                            <label class="small text-secondary text-uppercase fw-bold mb-2">Objet de la demande</label>
                            <div class="h5 fw-bold">{{ $request->request_object }}</div>
                        </div>

                        <h6 class="fw-bold mb-3 px-1">Créneaux choisis</h6>
                        <div class="row g-2 mb-4">
                            @foreach($request->time_slots as $slot)
                            <div class="col-auto">
                                <span class="badge bg-white text-primary border px-3 py-2 rounded-3 shadow-sm">
                                    <i class="fa-regular fa-clock me-1"></i> {{ $slot }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        <h6 class="fw-bold mb-3 px-1">Personnes concernées</h6>
                        <div class="space-y-2">
                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">1</div>
                                <div class="fw-bold">{{ $request->name1 }}</div>
                            </div>
                            @if($request->name2)
                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">2</div>
                                <div class="fw-bold">{{ $request->name2 }}</div>
                            </div>
                            @endif
                            @if($request->name3)
                            <div class="p-3 bg-white border rounded-4 d-flex align-items-center mb-2">
                                <div class="rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">3</div>
                                <div class="fw-bold">{{ $request->name3 }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="small text-secondary text-uppercase fw-bold mb-1">Email</label>
                                    <div class="fw-bold">{{ $request->email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-4">
                                    <label class="small text-secondary text-uppercase fw-bold mb-1">Opérateur</label>
                                    <div class="fw-bold">{{ $request->payment_operator }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center p-4">
                            @if($request->payment_receipt)
                                @php
        $receiptUrl = $request->payment_receipt_url;
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
