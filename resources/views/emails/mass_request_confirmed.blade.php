<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande de messe</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f9; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #dc143c; padding: 30px; text-align: center; color: white; }
        .content { padding: 40px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 0.85rem; color: #6c757d; }
        .details-box { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 25px 0;  }
        .detail-row { margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .detail-label { font-weight: bold; color: #495057; display: block; font-size: 0.8rem; text-transform: uppercase; }
        .detail-value { font-size: 1rem; color: #111; }
        h2 { margin-top: 0; color: #dc143c; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 1.5rem;">Paroisse Saint Michel Archange</h1>
        </div>
        <div class="content">
            <h2 style="color: #198754;">Demande Validée !</h2>
            <p>Bonjour,</p>
            <p>Nous avons le plaisir de vous informer que votre demande d'intention de messe a été <strong>validée</strong> par notre secrétariat.</p>

            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Date de la messe :</span>
                    <span class="detail-value">{{ $massRequest->requested_date->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Créneaux horaires :</span>
                    <span class="detail-value">{{ implode(', ', $massRequest->time_slots) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Noms des personnes :</span>
                    <span class="detail-value">
                        {{ $massRequest->name1 }}
                        @if($massRequest->name2) , {{ $massRequest->name2 }} @endif
                        @if($massRequest->name3) , {{ $massRequest->name3 }} @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Objet de la demande :</span>
                    <span class="detail-value">{{ $massRequest->request_object }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Montant payé :</span>
                    <span class="detail-value">{{ number_format($massRequest->amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            <p>Nous vous remercions pour votre confiance et nous unissons nos prières aux vôtres.</p>
            
            <p>Cordialement,<br>Le Secrétariat de la Paroisse Saint Michel</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Saint Michel Archange d‘Adjamé. Tous droits réservés.
        </div>
    </div>
</body>
</html>
