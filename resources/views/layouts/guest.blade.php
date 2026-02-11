<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sacre Coeur - Connexion</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: crimson;
            --primary-light: #ebf5ff;
            --dark: #111827;
            --glass: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .auth-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            animation: fadeIn 0.6s ease-out;
        }

        .logo-box {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(88, 80, 236, 0.2);
        }

        .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(88, 80, 236, 0.1);
            background: white;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #892136;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(88, 80, 236, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-primary-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .text-primary-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="logo-box">
                <img src="{{ asset('assets/images/logo/logo.png') }}" style="height: 112px" alt="">
            </div>
            <h2 class="fw-bold text-dark">Saint Michel Archange</h2>
            <p class="text-secondary small">Veuillez vous authentifier pour continuer</p>
        </div>

        {{ $slot }}
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
