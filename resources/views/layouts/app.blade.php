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
            background-color: #f3f4f6;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
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
            box-shadow: 4px 0 15px rgba(0,0,0,0.03);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark);
            text-decoration: none;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary);
            margin-bottom: 0.75rem;
            padding-left: 1rem;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            color: var(--secondary);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-link.active i {
            color: var(--primary);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 2rem;
            max-width: calc(100vw - var(--sidebar-width));
        }

        /* Glass Cards */
        .glass-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .badge-pill {
            padding: 0.5em 1em;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #be123c;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
        }

        /* Dropdown Sidebar Styles */
        .dropdown-toggle-custom {
            cursor: pointer;
            text-align: left;
        }

        .arrow-icon {
            font-size: 0.7rem;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle-custom.open .arrow-icon {
            transform: rotate(90deg);
        }

        .submenu {
            display: none;
            padding-left: 3rem;
            margin-bottom: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .dropdown-toggle-custom.open + .submenu {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            color: var(--secondary);
            text-decoration: none;
            font-size: 0.85rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .submenu-link:hover, .submenu-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert {
            border-radius: 15px;
            border: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        /* Mobile Adjustments */
        .mobile-header {
            display: none;
            background: white;
            padding: 1rem 1.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
                transition: left 0.3s ease;
                height: 100vh;
                top: 0;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
                max-width: 100% !important;
                padding-top: 5rem;
            }

            .mobile-header {
                display: flex;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    @auth
        <div class="mobile-header">
            <div class="d-flex align-items-center gap-2">
                <div class="logo-box" style="width: 32px; height: 32px; font-size: 1rem;">
                    <i class="fa-solid fa-cross"></i>
                </div>
                <span class="fw-bold small">Saint Michel</span>
            </div>
            <button class="btn btn-light border-0 rounded-3" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
        </div>

        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-box">
                <img src="{{ asset('assets/images/logo/logo.png') }}" style="height: 112px" alt="">
                </div>
                <a href="{{ route('dashboard') }}" class="sidebar-brand">Saint Michel Archange</a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-label">Main Menu</div>
                @can('access_dashboard')
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                </div>
                @endcan

                <div class="nav-label">Modules</div>

                <!-- Logistique & Réservations -->
                @if(auth()->user()->hasAnyPermission(['access_reservations', 'access_rooms', 'access_time_slots', 'access_movements']))
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['reservations.*', 'rooms.*', 'time_slots.*', 'movements.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-hotel"></i> 
                        <span>Logistique</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon"></i>
                    </button>
                    <div class="submenu">
                        @can('access_reservations')
                        <a href="{{ route('reservations.index') }}" class="submenu-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-calendar-check me-2"></i> Réservations
                        </a>
                        @endcan
                        @can('access_rooms')
                        <a href="{{ route('rooms.index') }}" class="submenu-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-building-columns me-2"></i> Salles
                        </a>
                        @endcan
                        @can('access_time_slots')
                        <a href="{{ route('time_slots.index') }}" class="submenu-link {{ request()->routeIs('time_slots.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock me-2"></i> Créneaux
                        </a>
                        @endcan
                        @can('access_movements')
                        <a href="{{ route('movements.index') }}" class="submenu-link {{ request()->routeIs('movements.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-people-group me-2"></i> Mouvements
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- Inscriptions & Activités -->
                @if(auth()->user()->hasAnyPermission(['access_activities', 'access_registrations', 'access_presences']))
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['activities.*', 'admin.registrations.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-clipboard-list"></i> 
                        <span>Inscriptions</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon"></i>
                    </button>
                    <div class="submenu">
                        @can('access_registrations')
                        <a href="{{ route('admin.registrations.index') }}" class="submenu-link {{ request()->routeIs('admin.registrations.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-id-card me-2"></i> Liste Inscriptions
                        </a>
                        @endcan
                        @can('access_presences')
                        <a href="{{ route('admin.registrations.scanned') }}" class="submenu-link {{ request()->routeIs('admin.registrations.scanned') ? 'active' : '' }}">
                            <i class="fa-solid fa-barcode me-2"></i> Présences (Scans)
                        </a>
                        @endcan
                        @can('access_activities')
                        <a href="{{ route('activities.index') }}" class="submenu-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-person-walking me-2"></i> Activités
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <!-- Agents & Terrain -->
                @can('access_agents')
                <div class="nav-item">
                    <a href="{{ route('agents.index') }}" class="nav-link {{ request()->routeIs('agents.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-tie"></i> Agents 
                    </a>
                </div>
                @endcan

                @if(auth()->user()->hasAnyPermission(['manage_users', 'manage_roles']))
                <div class="nav-label">Administration</div>
                <div class="nav-item">
                    <button class="nav-link w-100 border-0 bg-transparent dropdown-toggle-custom {{ request()->routeIs(['users.*', 'roles.*']) ? 'active open' : '' }}" onclick="toggleDropdown(this)">
                        <i class="fa-solid fa-shield-halved"></i> 
                        <span>Accès & Sécurité</span>
                        <i class="fa-solid fa-chevron-right ms-auto arrow-icon"></i>
                    </button>
                    <div class="submenu">
                        @can('manage_users')
                        <a href="{{ route('users.index') }}" class="submenu-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users me-2"></i> Utilisateurs
                        </a>
                        @endcan
                        @can('manage_roles')
                        <a href="{{ route('roles.index') }}" class="submenu-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-key me-2"></i> Rôles & Accès
                        </a>
                        @endcan
                    </div>
                </div>
                @endif

                <div class="mt-auto">
                    <div class="glass-card mb-0 p-3 rounded-4 border-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                <i class="fa-solid fa-user small"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-bold text-truncate" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                                <div class="text-secondary text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 btn-sm">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>
    @endauth

    <main class="main-content" style="{{ !Auth::check() ? 'margin-left: 0; max-width: 100%;' : '' }}">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 animate-fade-in">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                    <div>{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4 animate-fade-in">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-exclamation fs-4 me-3"></i>
                    <div>{{ session('error') }}</div>
                </div>
            </div>
        @endif

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
</body>
</html>
