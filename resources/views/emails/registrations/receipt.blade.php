<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reçu d'Inscription</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }
        .header-left {
            display: inline-block;
            vertical-align: top;
        }
        .header-right {
            display: inline-block;
            vertical-align: top;
            text-align: right;
            float: right;
            color: #e11d48;
            font-weight: bold;
        }
        .logo-box {
            background-color: #991b1b;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            margin-right: 15px;
        }
        .logo-icon {
            color: white;
            font-size: 24px;
            line-height: 50px;
        }
        .title-text {
            display: inline-block;
            vertical-align: middle;
        }
        .title-text span {
            display: block;
            text-transform: uppercase;
            font-size: 12px;
            color: #e11d48;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .title-text h1 {
            margin: 0;
            font-size: 22px;
            color: #991b1b;
        }
        .main-title {
            margin-top: 50px;
            font-size: 36px;
            color: #7f1d1d;
            font-weight: 800;
            margin-bottom: 10px;
        }
        .title-underline {
            width: 80px;
            height: 5px;
            background-color: #e11d48;
            margin-bottom: 30px;
        }
        .divider {
            border-top: 1px solid #fecaca;
            margin: 30px 0;
        }
        .content-container {
            width: 100%;
            margin-top: 40px;
        }
        .info-col {
            width: 55%;
            display: inline-block;
            vertical-align: top;
        }
        .qr-col {
            width: 40%;
            display: inline-block;
            vertical-align: top;
            text-align: right;
            float: right;
        }
        .info-box {
            background-color: #fff1f2;
            border: 1px solid #fecaca;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-box label {
            display: block;
            text-transform: uppercase;
            font-size: 10px;
            color: #991b1b;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .info-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #7f1d1d;
        }
        .qr-container {
            background: white;
            padding: 15px;
            border-radius: 20px;
            display: inline-block;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #f1f5f9;
        }
        .footer-note {
            margin-top: 60px;
            text-align: center;
            font-size: 16px;
            color: #7f1d1d;
            font-style: italic;
            line-height: 1.6;
            padding: 0 40px;
        }
        .dots {
            text-align: center;
            margin-top: 30px;
        }
        .dot {
            height: 8px;
            width: 8px;
            background-color: #e11d48;
            border-radius: 50%;
            display: inline-block;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo-box">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Saint Michel Archange">
            </div>
            <div class="title-text">
                <span>REÇU</span>
                <h1>d'Inscription</h1>
            </div>
        </div>
        <div class="header-right">
            <span style="display: block; font-size: 10px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Date d'inscription</span>
            {{ $registrationDate ?? \Carbon\Carbon::now()->locale('fr')->translatedFormat('d F Y') }}
        </div>
    </div>

    <h2 class="main-title">{{ $activityTitle ?? 'Activité' }}</h2>
    <div class="title-underline"></div>

    <div class="divider"></div>

    <div class="content-container">
        <div class="info-col">
            <div class="info-box">
                <label>TYPE D'INSCRIPTION</label>
                <div class="value">{{ $registrationOption ?? 'N/A' }}</div>
            </div>
            <div class="info-box">
                <label>NOM DU PARTICIPANT</label>
                <div class="value">{{ $participantName ?? 'N/A' }}</div>
            </div>
            <div class="info-box" style="margin-bottom: 0;">
                <label>NUMÉRO DE RÉFÉRENCE</label>
                <div class="value" style="font-family: monospace; font-size: 14px;">{{ $uuid ?? 'N/A' }}</div>
                @if(isset($registrationOption) && strtolower($registrationOption) !== 'individuel' && !empty($groupName))
                    <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #fca5a5;">
                        <label>NOM DE LA RÉFÉRENCE</label>
                        <div class="value" style="font-size: 14px;">{{ $groupName }}</div>
                    </div>
                @endif
            </div>
        </div>
        <div class="qr-col">
            <div class="qr-container">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" width="200" height="200">
            </div>
        </div>
    </div>

    <div class="divider" style="margin-top: 50px;"></div>

    <div class="footer-note">
        Nous sommes ravis de vous accueillir à cet événement exceptionnel.<br>
        Votre participation enrichira nos échanges et contribuera au succès de cette rencontre.
    </div>

    <div class="dots">
        <span class="dot"></span>
        <span class="dot" style="opacity: 0.7;"></span>
        <span class="dot" style="opacity: 0.4;"></span>
    </div>
</body>
</html>
