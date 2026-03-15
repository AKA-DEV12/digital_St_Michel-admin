@extends('layouts.app')

@section('content')
<div class="mb-5 animate-fade-in">
    <div class="d-flex align-items-center gap-3 mb-2">
        <h1 class="h3 fw-bold mb-0" style="letter-spacing: -0.02em;">Guide Opérationnel</h1>
        <span class="badge bg-primary-light text-primary px-3 py-2 rounded-pill small fw-bold" style="font-size: 0.7rem;">V.1.0.1 - MANUEL COMPLET</span>
    </div>
    <p class="text-secondary mb-0">Apprenez à manipuler chaque module de la plateforme avec précision et efficacité.</p>
</div>

<div class="row g-4 animate-fade-in">
    <!-- Sommaire Interactif -->
    <div class="col-lg-3">
        <div class="bg-white rounded-4 shadow-sm border p-4 sticky-top" style="top: 100px; z-index: 10;">
            <h6 class="fw-bold mb-4 text-dark text-uppercase tracking-wider" style="font-size: 0.75rem;">MODULES À MAÎTRISER</h6>
            <nav id="doc-nav" class="nav flex-column gap-2">
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2 active" href="#clerge">
                    <div class="rounded-2 bg-primary bg-opacity-10 p-1">
                        <i class="fa-solid fa-user-tie text-primary" style="width: 14px;"></i>
                    </div>
                    1. Clergé & RDV
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#activites">
                    <div class="rounded-2 bg-success bg-opacity-10 p-1">
                        <i class="fa-solid fa-person-walking text-success" style="width: 14px;"></i>
                    </div>
                    2. Activités & Inscriptions
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#salles">
                    <div class="rounded-2 bg-warning bg-opacity-10 p-1">
                        <i class="fa-solid fa-hotel text-warning" style="width: 14px;"></i>
                    </div>
                    3. Salles & Réservations
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#blog">
                    <div class="rounded-2 bg-info bg-opacity-10 p-1">
                        <i class="fa-solid fa-pen-nib text-info" style="width: 14px;"></i>
                    </div>
                    4. Blog & Médias
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#agents">
                    <div class="rounded-2 bg-danger bg-opacity-10 p-1">
                        <i class="fa-solid fa-qrcode text-danger" style="width: 14px;"></i>
                    </div>
                    5. Agents & Terrain
                </a>
                <a class="nav-link px-0 text-secondary small fw-bold d-flex align-items-center gap-2" href="#securite">
                    <div class="rounded-2 bg-slate-900 bg-opacity-10 p-1">
                        <i class="fa-solid fa-shield-halved text-slate-900" style="width: 14px;"></i>
                    </div>
                    6. Sécurité & Rôles
                </a>
            </nav>
        </div>
    </div>

    <!-- Contenu Détaillé -->
    <div class="col-lg-9">
        <div class="bg-white rounded-4 shadow-sm border p-0 overflow-hidden">
            
            <!-- 1. Clergé -->
            <section id="clerge" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-primary text-white p-3 shadow-lg shadow-blue-100">
                        <i class="fa-solid fa-user-tie fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Gestion du Clergé & Rendez-vous</h2>
                        <div class="text-primary small fw-bold">POUR LE MINISTÈRE SPIRITUEL</div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-circle-plus me-2 text-primary"></i> Ajouter un Prêtre</h6>
                        <p class="small text-secondary leading-relaxed">
                            Allez dans <strong>Clergé > Ajouter</strong>. Renseignez le nom, le rôle et surtout les <strong>créneaux de disponibilité</strong>. Ces créneaux apparaîtront sur le site public pour les prises de RDV.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-calendar-check me-2 text-primary"></i> Valider un RDV</h6>
                        <p class="small text-secondary leading-relaxed">
                            Dans <strong>Rendez-vous</strong>, vous voyez les demandes entrantes. Vous pouvez <strong>Valider</strong> ou <strong>Annuler</strong> une demande. Le fidèle recevra un feedback visuel sur son statut.
                        </p>
                    </div>
                </div>
                <div class="alert bg-primary-light border-0 rounded-4 p-4 mb-0">
                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-lightbulb text-primary fs-4"></i>
                        <div class="small text-dark leading-relaxed">
                            <strong>Astuce :</strong> Si un prêtre est en voyage, utilisez le champ "Dates d'indisponibilité" pour bloquer automatiquement sa prise de RDV sur ces jours précis.
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Activités -->
            <section id="activites" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-success text-white p-3 shadow-lg shadow-emerald-100">
                        <i class="fa-solid fa-person-walking fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Activités & Inscriptions</h2>
                        <div class="text-success small fw-bold">PILOTAGE DES ÉVÉNEMENTS</div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3">Cycle de manipulation :</h6>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <span class="badge rounded-circle bg-success p-2">1</span>
                            <div class="small text-secondary mt-1"><strong>Création :</strong> Définissez le titre, le lieu, et les 4 numéros de paiement (Wave, MTN, Moov, Orange).</div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <span class="badge rounded-circle bg-success p-2">2</span>
                            <div class="small text-secondary mt-1"><strong>Confirmation :</strong> Allez dans <strong>Inscriptions</strong>. vérifiez le reçu de paiement joint. Si OK, cliquez sur "Confirmer". Un mail de confirmation avec QR Code est envoyé au fidèle.</div>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <span class="badge rounded-circle bg-success p-2">3</span>
                            <div class="small text-secondary mt-1"><strong>Groupage :</strong> Utilisez le module <strong>Groupes</strong> pour réunir plusieurs inscriptions confirmées sous une même entité (ex: "Chorale St-Michel").</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Réservations -->
            <section id="salles" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-warning text-white p-3 shadow-lg shadow-yellow-100">
                        <i class="fa-solid fa-hotel fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Salles & Réservations</h2>
                        <div class="text-warning small fw-bold">OPTIMISATION DES ESPACES</div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-4 border border-slate-100 mb-4">
                    <h6 class="fw-bold text-dark small mb-2">Comment manipuler les conflits ?</h6>
                    <p class="small text-secondary mb-0">
                        La plateforme bloque automatiquement toute réservation si la salle est déjà occupée sur le même créneau horaire. En tant qu'admin, vous pouvez libérer une salle en <strong>Annulant</strong> une réservation existante dans le module <strong>Réservations</strong>.
                    </p>
                </div>
                <div class="small text-secondary">
                    <strong>Étape 1 :</strong> Définissez vos salles (Nom, Capacité). <br>
                    <strong>Étape 2 :</strong> Définissez les créneaux horaires autorisés (ex: 08:00 - 10:00). <br>
                    <strong>Étape 3 :</strong> Suivez les demandes dans "Liste des Réservations".
                </div>
            </section>

            <!-- 4. Blog -->
            <section id="blog" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-info text-white p-3 shadow-lg shadow-blue-100">
                        <i class="fa-solid fa-pen-nib fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Blog & Médias Premium</h2>
                        <div class="text-info small fw-bold">RAYONNEMENT NUMÉRIQUE</div>
                    </div>
                </div>

                <p class="small text-secondary mb-4">Pour publier un contenu captivant :</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 border bg-light text-center">
                            <i class="fa-solid fa-image text-primary mb-2"></i>
                            <div class="small fw-bold">Images</div>
                            <div class="text-xs text-secondary">Utilisez des JPEG/PNG < 2Mo</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 border bg-light text-center">
                            <i class="fa-solid fa-video text-danger mb-2"></i>
                            <div class="small fw-bold">Vidéos</div>
                            <div class="text-xs text-secondary">Collez l'ID YouTube (ex: dQw4w9WgXcQ)</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-4 border bg-light text-center">
                            <i class="fa-solid fa-tags text-success mb-2"></i>
                            <div class="small fw-bold">SEO</div>
                            <div class="text-xs text-secondary">Ajoutez des Tags pour le référencement</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. Agents -->
            <section id="agents" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-danger text-white p-3 shadow-lg shadow-red-100">
                        <i class="fa-solid fa-qrcode fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Agents & Présences Terrain</h2>
                        <div class="text-danger small fw-bold">VALIDATION EN TEMPS RÉEL</div>
                    </div>
                </div>

                <div class="p-4 rounded-4 border bg-white">
                    <h6 class="fw-bold text-dark small mb-3">Protocole pour l'Agent :</h6>
                    <ol class="small text-secondary mb-0">
                        <li class="mb-2">L'agent se connecte avec son compte sur son smartphone.</li>
                        <li class="mb-2">Il scanne le QR Code présent sur le reçu du pèlerin.</li>
                        <li>La présence est immédiatement marquée comme "Scannée" dans l'administration centrale.</li>
                    </ol>
                </div>
            </section>

            <!-- 6. Sécurité -->
            <section id="securite" class="p-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-slate-900 text-white p-3 shadow-lg">
                        <i class="fa-solid fa-shield-halved fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Sécurité & Rôles</h2>
                        <div class="text-slate-600 small fw-bold">CONTRÔLE D'ACCÈS</div>
                    </div>
                </div>

                <ul class="list-group list-group-flush small">
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div><strong>Super Admin</strong> - Accès total (Inclus: Paramètres, Rôles, Utilisateurs)</div>
                        <i class="fa-solid fa-lock-open text-success"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div><strong>Manager</strong> - Gestion des activités, blog et réservations.</div>
                        <i class="fa-solid fa-lock text-warning"></i>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div><strong>Agent</strong> - Uniquement le scanning de QR Codes.</div>
                        <i class="fa-solid fa-mobile text-primary"></i>
                    </li>
                </ul>
            </section>

        </div>
    </div>
</div>

<style>
    .leading-relaxed { line-height: 1.8; }
    .nav-link { transition: all 0.2s ease; border-radius: 12px; margin-bottom: 5px; }
    .nav-link:hover { background-color: #f8fafc; color: var(--primary) !important; transform: translateX(5px); }
    .nav-link.active { background-color: #fff1f2; color: var(--primary) !important; }
    .bg-primary-light { background-color: #fff1f2; }
    .sticky-top { transition: top 0.3s ease; }
    section { scroll-margin-top: 100px; }
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
