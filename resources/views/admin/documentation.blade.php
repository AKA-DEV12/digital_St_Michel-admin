@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex align-items-center gap-3 mb-2">
        <h1 class="h3 fw-bold mb-0" style="letter-spacing: -0.02em;">Guide d'Utilisation</h1>
        <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill small fw-bold" style="font-size: 0.7rem;">VERSION 1.0.1</span>
    </div>
    <p class="text-secondary mb-0">Découvrez comment exploiter tout le potentiel de la plateforme Digital Saint-Michel.</p>
</div>

<div class="row g-4 animate-fade-in">
    <!-- Sommaire -->
    <div class="col-lg-3">
        <div class="bg-white rounded-4 shadow-sm border p-4 sticky-top" style="top: 100px; z-index: 10;">
            <h6 class="fw-bold mb-4 text-dark text-uppercase tracking-wider" style="font-size: 0.75rem;">SÉCTIONS DU GUIDE</h6>
            <nav id="doc-nav" class="nav flex-column gap-2">
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2 active" href="#intro">
                    <div class="rounded-2 bg-primary bg-opacity-10 p-1">
                        <i class="fa-solid fa-book text-primary" style="width: 14px;"></i>
                    </div>
                    Introduction
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#modules">
                    <div class="rounded-2 bg-success bg-opacity-10 p-1">
                        <i class="fa-solid fa-cubes text-success" style="width: 14px;"></i>
                    </div>
                    Modules & Utilité
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#guide">
                    <div class="rounded-2 bg-warning bg-opacity-10 p-1">
                        <i class="fa-solid fa-gears text-warning" style="width: 14px;"></i>
                    </div>
                    Guide Pratique
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#evolutif">
                    <div class="rounded-2 bg-info bg-opacity-10 p-1">
                        <i class="fa-solid fa-rocket text-info" style="width: 14px;"></i>
                    </div>
                    Évolutions Futures
                </a>
            </nav>
            <hr class="my-4 opacity-50">
            <div class="rounded-3 p-3 bg-slate-50 border border-slate-100">
                <p class="small text-secondary mb-0"><i class="fa-solid fa-circle-info me-2 text-primary"></i>Besoin de plus d'aide ? Contactez l'administrateur système.</p>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="col-lg-9">
        <div class="bg-white rounded-4 shadow-sm border p-5">
            <!-- Intro -->
            <section id="intro" class="mb-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-primary text-white p-3 shadow-lg shadow-blue-100">
                        <i class="fa-solid fa-church fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Introduction & Contexte</h2>
                        <div class="text-primary small fw-bold">MODERNISATION PAROISSIALE</div>
                    </div>
                </div>
                <p class="text-secondary leading-relaxed">
                    La plateforme <strong>Digital Saint-Michel</strong> est née de la volonté de moderniser et de centraliser la gestion des activités paroissiales et pèlerines. Dans un monde de plus en plus connecté, cette solution offre un pont numérique entre l'administration et les fidèles, garantissant une organisation fluide, transparente et sécurisée.
                </p>
                <div class="p-4 rounded-4 border-start border-4 border-primary bg-primary bg-opacity-5 mt-4">
                    <p class="mb-0 fw-bold text-dark italic">"Propulser la gestion de la communauté vers l'excellence numérique tout en simplifiant les processus d'inscription, de réservation et d'interaction spirituelle."</p>
                </div>
            </section>

            <hr class="my-5 opacity-50">

            <!-- Modules -->
            <section id="modules" class="mb-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-success text-white p-3 shadow-lg shadow-emerald-100">
                        <i class="fa-solid fa-puzzle-piece fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Architecture & Modules</h2>
                        <div class="text-success small fw-bold">CŒUR DU SYSTÈME</div>
                    </div>
                </div>
                
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 border bg-white h-100 transition hover-shadow">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-user-tie me-2 text-primary"></i> Clergé (Priests)</h6>
                            <p class="small text-secondary mb-0">Humanise la plateforme en présentant les prêtres et gère l'agenda spirituel pour les rendez-vous individuels.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 border bg-white h-100 transition hover-shadow">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-person-walking me-2 text-success"></i> Activités & Pèlerinages</h6>
                            <p class="small text-secondary mb-0">Moteur événementiel gérant les inscriptions, les paiements et les groupes de participants.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 border bg-white h-100 transition hover-shadow">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-hotel me-2 text-warning"></i> Réservations de Salles</h6>
                            <p class="small text-secondary mb-0">Optimise l'utilisation des espaces physiques grâce à un système anti-collision par créneaux horaires.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-4 rounded-4 border bg-white h-100 transition hover-shadow">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-newspaper me-2 text-info"></i> Blog & Communication</h6>
                            <p class="small text-secondary mb-0">Rayonnement numérique avec un CMS premium supportant vidéos, tags et avis des lecteurs.</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="p-4 rounded-4 border bg-slate-50 text-dark">
                            <h6 class="fw-bold mb-2"><i class="fa-solid fa-mobile-screen me-2 text-crimson-600"></i> Agents Mobile & Présences</h6>
                            <p class="small text-secondary mb-0">Pont numérique/physique via QR Code pour la validation de présence sur le terrain en temps réel.</p>
                        </div>
                    </div>
                </div>
            </section>

            <hr class="my-5 opacity-50">

            <!-- Guide Pratique -->
            <section id="guide" class="mb-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-warning text-white p-3 shadow-lg shadow-yellow-100">
                        <i class="fa-solid fa-wand-magic-sparkles fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Guide d'Utilisation Pratique</h2>
                        <div class="text-warning small fw-bold">MAÎTRISE DE L'OUTIL</div>
                    </div>
                </div>

                <div class="bg-light bg-opacity-50 p-4 rounded-4 border border-dashed mb-4">
                    <h6 class="fw-bold text-dark mb-3">1. Tour de contrôle (Dashboard)</h6>
                    <ul class="text-secondary small ps-3 mb-0">
                        <li class="mb-2">Visualisez les statistiques en direct de la plateforme.</li>
                        <li class="mb-2">Utilisez les <strong>Actions Rapides</strong> pour gagner du temps.</li>
                        <li>Accédez au support technique via le bloc crimson en bas de page.</li>
                    </ul>
                </div>

                <div class="bg-light bg-opacity-50 p-4 rounded-4 border border-dashed mb-4">
                    <h6 class="fw-bold text-dark mb-3">2. Organisation d'un Pèlerinage</h6>
                    <ul class="text-secondary small ps-3 mb-0">
                        <li class="mb-2">Créez l'activité avec les détails de paiement nécessaires.</li>
                        <li class="mb-2">Utilisez le module <strong>Groupes</strong> pour organiser les fidèles (ex: Familles, Paroisses).</li>
                        <li>Validez les paiements via le reçu téléchargé par le fidèle.</li>
                    </ul>
                </div>

                <div class="bg-light bg-opacity-50 p-4 rounded-4 border border-dashed">
                    <h6 class="fw-bold text-dark mb-3">3. Communication Urgente</h6>
                    <p class="text-secondary small">Utilisez les <strong>Messages Flash</strong> pour une diffusion instantanée sur le portail public (alertes météo, changement d'horaire de messe).</p>
                </div>
            </section>

            <hr class="my-5 opacity-50">

            <!-- Futur -->
            <section id="evolutif" class="mb-4">
                <div class="rounded-5 p-5 text-white shadow-xl position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                    <div class="position-relative z-index-1">
                        <h2 class="h4 fw-bold mb-3">Une Plateforme Évolutivive</h2>
                        <p class="opacity-80 leading-relaxed mb-4">
                            La version <strong>V.1.0.1</strong> n'est que le commencement. Nous avons conçu cette plateforme pour qu'elle puisse grandir avec vous. De nouvelles fonctionnalités seront ajoutées progressivement au fil de vos suggestions et des besoins de la communauté paroissiale.
                        </p>
                        <hr class="opacity-20 my-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-10 p-2">
                                <i class="fa-solid fa-check text-white"></i>
                            </div>
                            <span class="small fw-bold">Digital Saint-Michel - L'excellence spirituelle au service du numérique.</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-rocket position-absolute opacity-10" style="right: -40px; bottom: -40px; font-size: 15rem; transform: rotate(-15deg);"></i>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .leading-relaxed { line-height: 1.8; }
    .nav-link { transition: all 0.2s ease; border-radius: 12px; }
    .nav-link:hover { background-color: #f8fafc; color: var(--primary) !important; transform: translateX(5px); }
    .nav-link.active { background-color: #fff1f2; color: var(--primary) !important; }
    .transition { transition: all 0.3s ease; }
    .hover-shadow:hover { shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1); transform: translateY(-3px); border-color: var(--primary) !important; }
    .sticky-top { transition: top 0.3s ease; }
</style>

<script>
    // Simple Scrollspy
    window.addEventListener('scroll', () => {
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('#doc-nav .nav-link');
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 150) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').includes(current)) {
                link.classList.add('active');
            }
        });
    });
</script>
@endsection
