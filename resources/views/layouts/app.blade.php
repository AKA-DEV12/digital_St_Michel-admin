<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacre Coeur - Administration</title>
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
    </style>
</head>
<body>
    @auth
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-box">
                <img src="{{ asset('assets/images/logo/logo.png') }}" style="height: 112px" alt="">
                </div>
                <a href="{{ route('dashboard') }}" class="sidebar-brand">Saint Michel Archange</a>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-label">Main Menu</div>
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                </div>

                <div class="nav-label">Management</div>
                <div class="nav-item">
                    <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-check"></i> Réservations
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-building-columns"></i> Salles
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('time_slots.index') }}" class="nav-link {{ request()->routeIs('time_slots.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-clock"></i> Créneaux
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('movements.index') }}" class="nav-link {{ request()->routeIs('movements.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-people-group"></i> Mouvements
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('activities.index') }}" class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-person-walking"></i> Activités
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-id-card"></i> Inscriptions
                    </a>
                </div>

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
</body>
</html>
