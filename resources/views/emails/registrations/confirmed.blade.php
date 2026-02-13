<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #3490dc; padding-bottom: 20px; margin-bottom: 20px; }
        .activity-info { background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .participant-list { margin-bottom: 20px; }
        .participant-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .footer { text-align: center; font-size: 0.8em; color: #777; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #3490dc; margin: 0;">Confirmation d'Inscription</h2>
        </div>

        <p>Bonjour,</p>
        <p>Nous avons le plaisir de vous confirmer votre inscription pour l'activité suivante :</p>

        <div class="activity-info">
            <h3 style="margin: 0 0 10px 0;">{{ $activityTitle }}</h3>
            <p style="margin: 0;"><strong>Date :</strong> {{ $activityDate }}</p>
            <p style="margin: 0;"><strong>Option :</strong> {{ $registrationOption }}</p>
        </div>

        <p>Veuillez trouver en pièces jointes vos tickets d'entrée sous forme de QR codes. **Chaque participant doit présenter son QR code à l'entrée.**</p>

        <div class="participant-list">
            <h4>Liste des participants :</h4>
            @foreach($participants as $participant)
                <div class="participant-item">
                    <strong>{{ $participant->full_name }}</strong><br>
                    <small>{{ $participant->phone_number }}</small>
                </div>
            @endforeach
        </div>

        <p>Merci de votre confiance et à bientôt !</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Saint Michel Archange d‘Adjamé - Paroisse SMA. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
