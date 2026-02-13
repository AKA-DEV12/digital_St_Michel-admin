<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statut de votre réservation</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f7f9; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: crimson; padding: 30px; text-align: center; color: white; }
        .header.cancelled { background-color: #dc3545; }
        .content { padding: 40px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 0.85rem; color: #6c757d; }
        .details-box { background-color: #f8f9fa; border-radius: 8px; padding: 20px; margin: 25px 0;  }
       
        .detail-row { margin-bottom: 10px; display: flex; justify-content: space-between; }
        .detail-label { font-weight: bold; color: #495057; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 20px; }
        h2 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $reservation->status === 'cancelled' ? 'cancelled' : '' }}">
            <h1 style="margin:0; font-size: 1.5rem;">Saint Michel</h1>
        </div>
        <div class="content">
            @if($reservation->status === 'validated')
                <h2 style="color: #198754;">Bonne nouvelle !</h2>
                <p>Bonjour <strong>{{ $reservation->first_name }}</strong>,</p>
                <p>Nous avons le plaisir de vous informer que votre demande de réservation a été <strong>acceptée</strong>.</p>
            @else
                <h2 style="color: #dc3545;">Mise à jour de réservation</h2>
                <p>Bonjour <strong>{{ $reservation->first_name }}</strong>,</p>
                <p>Nous vous informons que votre demande de réservation a été <strong>annulée</strong>.</p>
            @endif

            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Objet :</span>
                    <span>{{ $reservation->reservation_object }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Salle :</span>
                    <span>{{ $reservation->room->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date :</span>
                    <span>{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Horaire :</span>
                    <span>{{ $reservation->time_slot }}</span>
                </div>
            </div>

            <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
            
            <p>Cordialement,<br>L'administration de Saint Michel</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Saint Michel Archange d‘Adjamé - Paroisse SMA. Tous droits réservés.
        </div>
    </div>
</body>
</html>
