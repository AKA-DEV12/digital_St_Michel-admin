<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $appointment->status === 'validated' ? 'Confirmation' : 'Annulation' }} de Rendez-vous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: {{ $appointment->status === 'validated' ? '#059669' : '#dc2626' }};
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #374151;
            line-height: 1.6;
        }
        .details {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($appointment->status === 'validated')
                <h1>Votre rendez-vous est confirmé</h1>
            @else
                <h1>Votre rendez-vous a été annulé</h1>
            @endif
        </div>
        
        <div class="content">
            <p>Bonjour {{ $appointment->full_name }},</p>
            
            @if($appointment->status === 'validated')
                <p>Nous avons le plaisir de vous informer que votre demande de rendez-vous avec <strong>Père {{ $appointment->priest->first_name }} {{ $appointment->priest->last_name }}</strong> a été validée avec succès.</p>
            @else
                <p>Nous sommes au regret de vous informer que votre rendez-vous avec <strong>Père {{ $appointment->priest->first_name }} {{ $appointment->priest->last_name }}</strong> prévu pour cette date a dû être annulé. Nous vous invitons à soumettre une nouvelle demande via notre plateforme.</p>
            @endif

            <div class="details">
                @php
                    \Carbon\Carbon::setLocale('fr');

                    $date = \Carbon\Carbon::parse($appointment->appointment_date)
                @endphp
                <p><strong>Date du rendez-vous :</strong> {{ $date->translatedFormat('l d F Y') }}</p>
                <p><strong>Créneau horaire :</strong> {{ $appointment->time_slot }}</p>
                <p><strong>Objet :</strong> {{ $appointment->object }}</p>
            </div>
            
            @if($appointment->status === 'validated')
                <p>Nous vous remercions de vous présenter à l'heure convenue à la Paroisse. En cas d'empêchement, merci de nous prévenir à l'avance.</p>
            @endif

            <p>Fraternellement,</p>
            <p><strong>Le secrétariat de la Paroisse Saint Michel Archange d'Adjamé</strong></p>
        </div>
        
        <div class="footer">
            Cet e-mail est généré automatiquement, merci de ne pas y répondre directement.
        </div>
    </div>
</body>
</html>
