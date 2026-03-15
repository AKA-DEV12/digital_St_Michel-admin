<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Service Saint-Michel - Administration</title>
    <!--Favicon-->
    <link rel="icon" href="{{ asset('assets/images/logo/logo.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <!-- Scripts & Styles (Tailwind via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        :root {
            --primary: #dc143c;
            --primary-light: #fff1f2;
            --secondary: #6b7280;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #111827;
            --sidebar-width: 280px;
            --glass: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            color: var(--dark);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 90px 2rem 2rem;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease;
            overflow-x: hidden;
        }

        /* Glass Cards */
        .glass-card {
            background: white;
            border: 1px solid #eef2f6;
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .glass-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #eef2f6;
            z-index: 1050;
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #f8fafc;
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--dark);
        }

        .sidebar-brand img {
            width: 60px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Topbar Styling */
        .topbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #eef2f6;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 1040;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            transition: all 0.3s ease;
        }

        .search-bar {
            position: relative;
            width: 300px;
        }

        .search-bar i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary);
            font-size: 0.9rem;
        }

        .search-bar input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.8rem;
            border-radius: 12px;
            border: 1px solid #eef2f6;
            background: #f8fafc;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .search-bar input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 14px;
            border: none;
            background: transparent;
            transition: all 0.2s ease;
        }

        .user-dropdown-toggle:hover {
            background: #f8fafc;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary) 0%, #be123c 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(220, 20, 60, 0.2);
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary);
            font-weight: 500;
        }

        .badge-pill {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        /* Premium Table Styling */
        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-top: -8px;
        }

        .premium-table thead th {
            background: #f8fafc;
            padding: 1.25rem 1.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary);
            border: none;
        }

        .premium-table thead th:first-child { border-radius: 12px 0 0 12px; }
        .premium-table thead th:last-child { border-radius: 0 12px 12px 0; }

        .premium-table tbody tr {
            background: white;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .premium-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.05);
            z-index: 1;
        }

        .premium-table tbody td {
            padding: 1.25rem 1.5rem;
            border: none;
            vertical-align: middle;
        }

        .premium-table tbody td:first-child { border-radius: 15px 0 0 15px; border-left: 1px solid #eef2f6; }
        .premium-table tbody td:last-child { border-radius: 0 15px 15px 0; border-right: 1px solid #eef2f6; }
        .premium-table tbody td { border-top: 1px solid #eef2f6; border-bottom: 1px solid #eef2f6; }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #be123c;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 20, 60, 0.25);
        }

        /* Nav Links */
        .nav-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin: 1.5rem 0 0.75rem;
            padding-left: 1rem;
            font-weight: 700;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem 1.25rem;
            color: #64748b;
            text-decoration: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            width: 100%;
        }

        .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: #f8fafc;
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .submenu-link {
            display: block;
            padding: 0.6rem 1.25rem 0.6rem 3.25rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .submenu-link:hover, .submenu-link.active {
            color: var(--primary);
            background: #f8fafc;
        }

        /* Dropdown Functionality */
        .submenu {
            display: none;
            overflow: hidden;
            animation: slideDown 0.3s ease-out;
        }

        .nav-link.open + .submenu {
            display: block;
        }

        .arrow-icon {
            transition: transform 0.3s ease;
        }

        .nav-link.open .arrow-icon {
            transform: rotate(90deg);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Mobile Adjustments */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(4px);
            z-index: 1045;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
                padding-top: 80px;
                width: 100%;
            }

            .topbar {
                left: 0 !important;
                padding: 0 1rem;
            }

            .sidebar-overlay.show {
                display: block;
            }
            
            .search-bar {
                display: none;
            }
        }

        /* Tag Selection Styles */
        .tag-check input:checked + label {
            background-color: var(--primary) !important;
            color: white !important;
            border-color: var(--primary) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .tag-check label:hover {
            border-color: var(--primary);
            color: var(--primary);
        }
        .cursor-pointer { cursor: pointer; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @auth
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Saint Michel">
                    <span class="fw-bold" style="font-size: 1.1rem; letter-spacing: -0.02em;">Saint Michel</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-label">Tableau de bord</div>
                @can('access_dashboard')
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-grid-2"></i> Dashboard
                    </a>
                </div>
                @endcan

                <div class="nav-label">Gestion</div>

                <!-- Logistique & Réservations -->
                @if(auth()->user()->hasAnyPermission(['access_reservations', 'access_rooms', 'access_time_slots', 'access_movements']))
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['reservations.*', 'rooms.*', 'time_slots.*', 'movements.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-building-columns"></i> 
                        <span>Logistique</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon" style="font-size: 0.6rem;"></i>
                    </button>
                    <div class="submenu">
                        @can('access_reservations')
                        <a href="{{ route('reservations.index') }}" class="submenu-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                            Réservations
                        </a>
                        @endcan
                        @can('access_rooms')
                        <a href="{{ route('rooms.index') }}" class="submenu-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            Salles
                        </a>
                        @endcan
                        @can('access_time_slots')
                        <a href="{{ route('time_slots.index') }}" class="submenu-link {{ request()->routeIs('time_slots.*') ? 'active' : '' }}">
                            Créneaux
                        </a>
                        @endcan
                        @can('access_movements')
                        <a href="{{ route('movements.index') }}" class="submenu-link {{ request()->routeIs('movements.*') ? 'active' : '' }}">
                            Mouvements
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- Clergé & RDV -->
                @can('access_priests')
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['admin.priests.*', 'admin.priest_appointments.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-user-tie"></i> 
                        <span>Clergé & RDV</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon" style="font-size: 0.6rem;"></i>
                    </button>
                    <div class="submenu">
                        <a href="{{ route('admin.priests.index') }}" class="submenu-link {{ request()->routeIs('admin.priests.*') ? 'active' : '' }}">
                            Pères
                        </a>
                        <a href="{{ route('admin.priest_appointments.index') }}" class="submenu-link {{ request()->routeIs('admin.priest_appointments.*') ? 'active' : '' }}">
                            Rendez-vous
                        </a>
                    </div>
                </div>
                @endcan
                
                <div class="nav-label">Public</div>

                <!-- Inscriptions & Activités -->
                @if(auth()->user()->hasAnyPermission(['access_activities', 'access_registrations', 'access_presences']))
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['activities.*', 'admin.registrations.*', 'admin.participant_groups.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-users"></i> 
                        <span>Inscriptions</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon" style="font-size: 0.6rem;"></i>
                    </button>
                    <div class="submenu">
                        @can('access_registrations')
                        <a href="{{ route('admin.registrations.selector', ['target' => 'registrations']) }}" class="submenu-link {{ (request()->routeIs('admin.registrations.index') || (request()->routeIs('admin.registrations.selector') && request('target') == 'registrations')) ? 'active' : '' }}">
                            Inscriptions
                        </a>
                        @endcan
                        @can('access_presences')
                        <a href="{{ route('admin.registrations.selector', ['target' => 'scanned']) }}" class="submenu-link {{ (request()->routeIs('admin.registrations.scanned') || (request()->routeIs('admin.registrations.selector') && request('target') == 'scanned')) ? 'active' : '' }}">
                            Présences
                        </a>
                        @endcan
                        @can('access_activities')
                        <a href="{{ route('activities.index') }}" class="submenu-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            Activités
                        </a>
                        @endcan
                        @can('access_registrations')
                        <a href="{{ route('admin.registrations.selector', ['target' => 'groups']) }}" class="submenu-link {{ (request()->routeIs('admin.participant_groups.*') || (request()->routeIs('admin.registrations.selector') && request('target') == 'groups')) ? 'active' : '' }}">
                            Groupes
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- Blog Management -->
                @if(auth()->user()->hasAnyPermission(['access_blog', 'access_flash_messages', 'access_settings']))
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ (request()->routeIs('admin.blog.*') || request()->routeIs('admin.flash-messages.*') || request()->routeIs('admin.settings.*')) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-blog"></i> 
                        <span>Contenu & Web</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon" style="font-size: 0.6rem;"></i>
                    </button>
                    <div class="submenu">
                        @can('access_blog')
                        <a href="{{ route('admin.blog.index') }}" class="submenu-link {{ request()->routeIs('admin.blog.index') ? 'active' : '' }}">
                            Articles
                        </a>
                        <a href="{{ route('admin.blog.categories') }}" class="submenu-link {{ request()->routeIs('admin.blog.categories') ? 'active' : '' }}">
                            Catégories
                        </a>
                        <a href="{{ route('admin.blog.tags') }}" class="submenu-link {{ request()->routeIs('admin.blog.tags') ? 'active' : '' }}">
                            Tags
                        </a>
                        @endcan
                        @can('access_flash_messages')
                        <a href="{{ route('admin.flash-messages.index') }}" class="submenu-link {{ request()->routeIs('admin.flash-messages.*') ? 'active' : '' }}">
                            Messages Flash
                        </a>
                        @endcan
                        @can('access_settings')
                        <a href="{{ route('admin.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            Configuration
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                @if(auth()->user()->hasAnyPermission(['manage_users', 'manage_roles']))
                <div class="nav-label">Système</div>
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['users.*', 'roles.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-lock"></i> 
                        <span>Sécurité</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon" style="font-size: 0.6rem;"></i>
                    </button>
                    <div class="submenu">
                        @can('access_agents')
                        <a href="{{ route('agents.index') }}" class="submenu-link {{ request()->routeIs('agents.*') ? 'active' : '' }}">
                            Agents Mobile
                        </a>
                        @endcan
                        @can('manage_users')
                        <a href="{{ route('users.index') }}" class="submenu-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            Utilisateurs
                        </a>
                        @endcan
                        @can('manage_roles')
                        <a href="{{ route('roles.index') }}" class="submenu-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            Rôles
                        </a>
                        @endcan
                    </div>
                </div>
                @endif
            </nav>
        </aside>

        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn d-lg-none p-0 border-0 fs-4" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Recherche rapide...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="user-dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <div class="user-info d-none d-sm-block">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">{{ Auth::user()->roles->first()->name ?? 'Utilisateur' }}</div>
                        </div>
                        <i class="fa-solid fa-chevron-down opacity-50 ms-1" style="font-size: 0.7rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 mt-2 animate-fade-in" style="min-width: 200px;">
                        <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}"><i class="fa-regular fa-user me-2"></i> Mon Profil</a></li>
                        <li><a class="dropdown-item rounded-3 py-2" href="{{ route('profile.edit') }}#password-section"><i class="fa-solid fa-lock me-2"></i> Mot de Passe</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger fw-600">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
    @endauth

    <main class="main-content" style="{{ !Auth::check() ? 'margin-left: 0; max-width: 100%; padding-top: 2rem;' : '' }}">
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                
                // Prevent body scroll when sidebar is open
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }

        function toggleDropdown(element) {
            element.classList.toggle('open');
            // Fechar outros dropdowns se desejar (opcional)
        }
        
        // Close sidebar on window resize if going back to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 991.98) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                if (sidebar) sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
    @include('partials.flashmessage')
    @stack('scripts')
</body>
</html>
