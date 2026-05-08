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
            
            <!-- 0. Dashboard -->
            <section id="dashboard" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-dark text-white p-3 shadow-lg">
                        <i class="fa-solid fa-chart-line fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Tableau de Bord & Business Intelligence</h2>
                        <div class="text-dark small fw-bold">PILOTAGE DATA-DRIVEN</div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-chart-pie me-2 text-primary"></i> Graphiques Interactifs</h6>
                        <p class="small text-secondary leading-relaxed">
                            Consultez en un coup d'œil l'<strong>état matrimonial</strong>, la <strong>répartition par âge</strong> et les <strong>profils professionnels</strong> de vos paroissiens. Ces données sont extraites automatiquement des membres de groupes.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-wallet me-2 text-primary"></i> Suivi des Revenus</h6>
                        <p class="small text-secondary leading-relaxed">
                            Le dashboard agrège les paiements validés des <strong>Messes</strong>, des <strong>Réservations</strong> et des <strong>Activités</strong> pour vous donner le revenu total en temps réel.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 1. Clergé -->
            <section id="clerge" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-primary text-white p-3 shadow-lg">
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
                            Allez dans <strong>Clergé > Ajouter</strong>. Renseignez le nom et les <strong>créneaux de disponibilité</strong>. Ces créneaux apparaîtront sur le site public pour les prises de RDV.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-calendar-check me-2 text-primary"></i> Valider un RDV</h6>
                        <p class="small text-secondary leading-relaxed">
                            Dans <strong>Rendez-vous</strong>, vous voyez les demandes. Cliquez sur <strong>Valider</strong> pour confirmer la rencontre.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 2. Activités & Groupes -->
            <section id="activites" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-success text-white p-3 shadow-lg">
                        <i class="fa-solid fa-users fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Activités & Groupes Paroissiaux</h2>
                        <div class="text-success small fw-bold">VIE DE LA COMMUNAUTÉ</div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-3">Saisie enrichie des membres :</h6>
                    <p class="small text-secondary mb-3">
                        Pour des statistiques précises sur le dashboard, veillez à renseigner lors de l'ajout d'un membre :
                    </p>
                    <div class="row g-2 mb-4">
                        <div class="col-md-4">
                            <div class="p-2 border rounded-3 bg-light small text-center">Date de naissance</div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 border rounded-3 bg-light small text-center">Situation Prof.</div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 border rounded-3 bg-light small text-center">Nombre d'enfants</div>
                        </div>
                    </div>
                    <div class="alert bg-success bg-opacity-10 border-0 rounded-4 p-3 small text-success">
                        <strong>Note :</strong> Seuls les membres dont la date de naissance est renseignée sont comptabilisés dans le graphique de répartition par âge.
                    </div>
                </div>
            </section>

            <!-- 7. Messes -->
            <section id="messes" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-indigo-600 text-white p-3 shadow-lg" style="background-color: #4f46e5;">
                        <i class="fa-solid fa-church fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Intentions de Messe</h2>
                        <div class="text-indigo-600 small fw-bold" style="color: #4f46e5;">DEMANDES SPIRITUELLES</div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-list-check me-2" style="color: #4f46e5;"></i> Traitement des demandes</h6>
                        <p class="small text-secondary mb-0">
                            Chaque demande apparaît dans <strong>Messes > Demandes</strong>. Vérifiez l'objet et le montant. Une fois le paiement confirmé, passez le statut à <strong>Payé (Confirmé)</strong>.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark"><i class="fa-solid fa-gear me-2" style="color: #4f46e5;"></i> Configuration</h6>
                        <p class="small text-secondary mb-0">
                            Dans l'onglet <strong>Configuration</strong>, vous pouvez modifier le prix unitaire d'une messe et mettre à jour les numéros de téléphone pour les paiements mobiles.
                        </p>
                    </div>
                </div>
            </section>

            <!-- 3. Réservations -->
            <section id="salles" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-warning text-white p-3 shadow-lg">
                        <i class="fa-solid fa-hotel fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Salles & Réservations</h2>
                        <div class="text-warning small fw-bold">OPTIMISATION DES ESPACES</div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-4 border border-slate-100 mb-0">
                    <h6 class="fw-bold text-dark small mb-2">Cycle de réservation</h6>
                    <p class="small text-secondary mb-0">
                        1. Création de la salle > 2. Définition des créneaux > 3. Validation de la demande (après vérification du paiement).
                    </p>
                </div>
            </section>

            <!-- 4. Blog -->
            <section id="blog" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-info text-white p-3 shadow-lg">
                        <i class="fa-solid fa-pen-nib fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Blog & Médias Premium</h2>
                        <div class="text-info small fw-bold">RAYONNEMENT NUMÉRIQUE</div>
                    </div>
                </div>

                <p class="small text-secondary mb-0">Utilisez l'ID YouTube pour les vidéos et des images de moins de 2Mo pour garantir un chargement rapide du site public.</p>
            </section>

            <!-- 5. Agents -->
            <section id="agents" class="p-5 border-bottom">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-4 bg-danger text-white p-3 shadow-lg">
                        <i class="fa-solid fa-qrcode fs-4"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold mb-1">Agents & Présences Terrain</h2>
                        <div class="text-danger small fw-bold">VALIDATION EN TEMPS RÉEL</div>
                    </div>
                </div>

                <p class="small text-secondary mb-0">L'agent scanne le QR Code du pèlerin via son smartphone pour marquer sa présence lors des activités.</p>
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

                <p class="small text-secondary mb-0">Gérez les accès via <strong>Paramètres > Rôles</strong> pour définir qui peut voir ou modifier chaque module.</p>
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
