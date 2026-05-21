<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Réservation de Salle</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #27ae60;
        }
        .info-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #333;
            font-size: 16px;
        }
        .highlight {
            background: #d5f4e6;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #27ae60;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .btn:hover {
            background: #229954;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏠 Nouvelle Réservation de Salle</h1>
        <p>Une nouvelle demande de réservation a été soumise et nécessite votre validation</p>
    </div>

    <div class="content">
        <div class="highlight">
            <strong>📅 Date de réservation:</strong> {{ $reservation->reservation_date->format('d/m/Y') }}<br>
            <strong>⏰ Créneaux:</strong> {{ $timeSlotsDisplay }}<br>
            <strong>💰 Montant:</strong> {{ number_format($reservation->price, 0, ',', ' ') }} FCFA
        </div>

        <h3>👤 Informations du réservataire</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nom complet</div>
                <div class="info-value">{{ $reservation->first_name }} {{ $reservation->last_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $reservation->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $reservation->phone }}</div>
            </div>
            @if($reservation->group_name)
            <div class="info-item">
                <div class="info-label">Groupe/Mouvement</div>
                <div class="info-value">{{ $reservation->group_name }}</div>
            </div>
            @endif
            <div class="info-item">
                <div class="info-label">Opérateur de paiement</div>
                <div class="info-value">{{ $reservation->payment_operator }}</div>
            </div>
            @if($reservation->room)
            <div class="info-item">
                <div class="info-label">Salle réservée</div>
                <div class="info-value">{{ $reservation->room->name }}</div>
            </div>
            @endif
        </div>

        <h3>📝 Objet de la réservation</h3>
        <div class="info-item">
            <div class="info-value">{{ $reservation->reservation_object }}</div>
        </div>

        @if($reservation->payment_receipt)
        <h3>🧾 Preuve de paiement</h3>
        <div class="info-item">
            <div class="info-label">Statut</div>
            <div class="info-value">✅ Reçu de paiement téléchargé</div>
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/reservations/' . $reservation->id) }}" class="btn">
                Voir les détails complets
            </a>
        </div>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement depuis le système de gestion paroissiale.</p>
            <p>© {{ date('Y') }} - Paroisse Saint Michel Archange d'Adjamé</p>
        </div>
    </div>
</body>
</html>
