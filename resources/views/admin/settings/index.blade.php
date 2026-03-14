@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> 
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-5 animate-fade-in">
        <div>
            <h1 class="h3 fw-bold mb-1">Configuration du Site</h1>
            <p class="text-secondary">Gérez l'identité visuelle et les publicités du portail.</p>
        </div>
    </div>

    <div class="row g-4 animate-fade-in" style="animation-delay: 0.1s">
        <div class="col-12 col-md-8">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Identity Section -->
                    <div class="mb-5 pb-4 border-bottom">
                        <label class="form-label fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-id-card text-primary"></i> Identité du Portail
                        </label>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="small text-secondary mb-1">Nom du Site</label>
                                <input type="text" name="site_name" class="form-control rounded-3" value="{{ $settings['site_name'] ?? '' }}" placeholder="Paroisse Saint Michel Archange d'Adjamé">
                            </div>
                            <div class="col-md-12">
                                <label class="small text-secondary mb-1">Description (Footer)</label>
                                <textarea name="site_description" class="form-control rounded-3" rows="3" placeholder="Brève description de la paroisse pour le footer...">{{ $settings['site_description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Section -->
                    <div class="mb-5 pb-4 border-bottom">
                        <label class="form-label fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-image text-primary"></i> Logo du Site
                        </label>
                        <div class="d-flex align-items-center gap-4 p-3 border rounded-4 bg-light">
                            <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; overflow: hidden;">
                                @if(isset($settings['site_logo']))
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="img-fluid" alt="Current Logo">
                                @else
                                    <i class="fa-solid fa-photo-film fs-1 text-secondary opacity-25"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" name="site_logo" class="form-control mb-2 rounded-3">
                                <small class="text-secondary">Recommandé : PNG transparent, Max 2MB.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-5 pb-4 border-bottom">
                        <label class="form-label fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-address-book text-primary"></i> Informations de Contact
                        </label>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="small text-secondary mb-1">Adresse Physique</label>
                                <input type="text" name="site_address" class="form-control rounded-3" value="{{ $settings['site_address'] ?? '' }}" placeholder="Adjamé Fraternité, Abidjan, Côte d'Ivoire">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-secondary mb-1">Téléphone</label>
                                <input type="text" name="site_phone" class="form-control rounded-3" value="{{ $settings['site_phone'] ?? '' }}" placeholder="+225 XX XX XX XX XX">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-secondary mb-1">E-mail de contact</label>
                                <input type="email" name="site_email" class="form-control rounded-3" value="{{ $settings['site_email'] ?? '' }}" placeholder="contact@domaine.com">
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mb-5 pb-4 border-bottom">
                        <label class="form-label fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-share-nodes text-primary"></i> Réseaux Sociaux
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-brands fa-facebook text-primary"></i></span>
                                    <input type="url" name="facebook_url" class="form-control border-start-0 ps-0" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-brands fa-twitter text-info"></i></span>
                                    <input type="url" name="twitter_url" class="form-control border-start-0 ps-0" value="{{ $settings['twitter_url'] ?? '' }}" placeholder="https://twitter.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-brands fa-youtube text-danger"></i></span>
                                    <input type="url" name="youtube_url" class="form-control border-start-0 ps-0" value="{{ $settings['youtube_url'] ?? '' }}" placeholder="https://youtube.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-brands fa-instagram text-danger"></i></span>
                                    <input type="url" name="instagram_url" class="form-control border-start-0 ps-0" value="{{ $settings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fa-brands fa-linkedin text-primary"></i></span>
                                    <input type="url" name="linkedin_url" class="form-control border-start-0 ps-0" value="{{ $settings['linkedin_url'] ?? '' }}" placeholder="https://linkedin.com/...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Header Ad Section -->
                    <div class="mb-5 pb-4 border-bottom">
                        <label class="form-label fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-rectangle-ad text-primary"></i> Publicité Header (Flyer)
                        </label>
                        <div class="border rounded-4 bg-light p-3">
                            <div class="mb-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center overflow-hidden" style="height: 150px;">
                                @if(isset($settings['header_ad_flyer']))
                                    <img src="{{ asset('storage/' . $settings['header_ad_flyer']) }}" class="h-100" alt="Current Ad">
                                @else
                                    <i class="fa-solid fa-image fs-1 text-secondary opacity-25"></i>
                                @endif
                            </div>
                            <input type="file" name="header_ad_flyer" class="form-control mb-2 rounded-3">
                            <small class="text-secondary">Format panoramique conseillé (ex: 728x90px), Max 2MB.</small>
                        </div>
                    </div>

                    <!-- Digital Service Ad Section -->
                    <div class="mb-5 pb-4">
                        <label class="form-label fw-bold mb-3 d-flex align-items-center gap-2">
                            <i class="fa-solid fa-bullhorn text-primary"></i> Publicité Sidebar (Digital Service)
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-secondary mb-1">Titre de la publicité</label>
                                <input type="text" name="ad_digital_service_title" class="form-control rounded-3" value="{{ $settings['ad_digital_service_title'] ?? '' }}" placeholder="Ex: Digital Service">
                            </div>
                            <div class="col-md-6">
                                <label class="small text-secondary mb-1">Texte de la publicité</label>
                                <input type="text" name="ad_digital_service_text" class="form-control rounded-3" value="{{ $settings['ad_digital_service_text'] ?? '' }}" placeholder="Ex: Solutions numériques innovantes">
                            </div>
                            <div class="col-md-12 text-secondary px-3 py-1">
                                <label class="small text-secondary mb-1">Lien de la publicité (Bouton)</label>
                                <input type="url" name="ad_digital_service_link" class="form-control rounded-3" value="{{ $settings['ad_digital_service_link'] ?? '' }}" placeholder="https://...">
                            </div>
                            <div class="col-md-12">
                                <label class="small text-secondary mb-1">Image de la publicité (Sidebar)</label>
                                <div class="border rounded-4 bg-light p-3">
                                    <div class="mb-3 bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center overflow-hidden" style="height: 150px;">
                                        @if(isset($settings['ad_digital_service_image']))
                                            <img src="{{ asset('storage/' . $settings['ad_digital_service_image']) }}" class="h-100" alt="Current Ad">
                                        @else
                                            <i class="fa-solid fa-image fs-1 text-secondary opacity-25"></i>
                                        @endif
                                    </div>
                                    <input type="file" name="ad_digital_service_image" class="form-control mb-2 rounded-3">
                                    <small class="text-secondary">Taille recommandée : 300x250px (Carré ou portrait), Max 2MB.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-primary rounded-3 px-5 py-2 fw-bold shadow-sm">
                            <i class="fa-solid fa-save me-2"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white h-100">
                <h5 class="fw-bold mb-4">Aperçu & Aide</h5>
                <div class="alert alert-info border-0 rounded-3">
                    <i class="fa-solid fa-circle-info me-2"></i> Les modifications seront répercutées instantanément sur la plateforme publique.
                </div>
                <p class="text-secondary small mt-4">
                    Assurez-vous que les images sont optimisées pour le web afin de ne pas ralentir le temps de chargement des pages.
                </p>
                <div class="mt-auto">
                    <hr class="opacity-10">
                    <p class="text-xs text-secondary mb-0 italic">Dernière mise à jour : {{ date('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
