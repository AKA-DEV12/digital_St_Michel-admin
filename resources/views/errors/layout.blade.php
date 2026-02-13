<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Saint Michel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #dc143c;
            --secondary: #0f172a;
        }
        body {
            background: radial-gradient(circle at center, #f8fafc 0%, #f1f5f9 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--secondary);
            overflow: hidden;
        }
        .error-container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
            animation: fadeIn 0.8s ease-out;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, #be123c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 10px 15px rgba(220, 20, 60, 0.2));
            animation: float 4s ease-in-out infinite;
        }
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .error-message {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 2.5rem;
        }
        .btn-home {
            background-color: var(--secondary);
            color: white;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.2);
            color: white;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">@yield('code')</div>
        <h1 class="error-title">@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn-home">
            <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</body>
</html>
