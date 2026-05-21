<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande de Messe</title>
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
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
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
            border-left: 4px solid #3498db;
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
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
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
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🙏 Nouvelle Demande de Messe</h1>
        <p>Une nouvelle intention de messe a été soumise et nécessite votre validation</p>
    </div>

    <div class="content">
        <div class="highlight">
            <strong>📅 Date de la messe:</strong> {{ $massRequest->requested_date->format('d/m/Y') }}<br>
            <strong>⏰ Créneaux:</strong> {{ $timeSlotsDisplay }}<br>
            <strong>💰 Montant:</strong> {{ number_format($massRequest->amount, 0, ',', ' ') }} FCFA
        </div>

        <h3>👤 Informations du demandeur</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nom principal</div>
                <div class="info-value">{{ $massRequest->name1 }}</div>
            </div>
            @if($massRequest->name2)
            <div class="info-item">
                <div class="info-label">Deuxième personne</div>
                <div class="info-value">{{ $massRequest->name2 }}</div>
            </div>
            @endif
            @if($massRequest->name3)
            <div class="info-item">
                <div class="info-label">Troisième personne</div>
                <div class="info-value">{{ $massRequest->name3 }}</div>
            </div>
            @endif
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $massRequest->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $massRequest->phone }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Opérateur de paiement</div>
                <div class="info-value">{{ $massRequest->payment_operator }}</div>
            </div>
        </div>

        <h3>📝 Objet de la demande</h3>
        <div class="info-item">
            <div class="info-value">{{ $massRequest->request_object }}</div>
        </div>

        @if($massRequest->payment_receipt)
        <h3>🧾 Preuve de paiement</h3>
        <div class="info-item">
            <div class="info-label">Statut</div>
            <div class="info-value">✅ Reçu de paiement téléchargé</div>
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/admin/mass-requests/' . $massRequest->id) }}" class="btn">
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
