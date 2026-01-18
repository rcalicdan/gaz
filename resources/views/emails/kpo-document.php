<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPO Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 0 0 5px 5px;
        }
        .document-info {
            background-color: white;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
            border-radius: 3px;
        }
        .document-info strong {
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        .custom-message {
            background-color: #e8f4f8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>📄 KPO Document</h2>
    </div>
    
    <div class="content">
        <p>Dzień dobry,</p>
        
        @if($customMessage)
        <div class="custom-message">
            {!! nl2br(e($customMessage)) !!}
        </div>
        @endif
        
        <p>W załączeniu przesyłamy dokument KPO (Oświadczenie dotyczące przekazania odpadów).</p>
        
        <div class="document-info">
            <strong>Numer KPO:</strong> {{ $kpoDocument->kpo_number }}<br>
            <strong>Data utworzenia:</strong> {{ $kpoDocument->created_at->format('d.m.Y H:i') }}<br>
            <strong>Kod odpadów:</strong> {{ $kpoDocument->waste_code }}<br>
            <strong>Ilość:</strong> {{ number_format($kpoDocument->quantity, 2) }} kg<br>
            @if($kpoDocument->client)
            <strong>Klient:</strong> {{ $kpoDocument->client->company_name }}
            @endif
        </div>
        
        <p>
            Dokument KPO znajduje się w załączniku PDF. Prosimy o zachowanie dokumentu dla celów ewidencyjnych.
        </p>
        
        <p>
            W razie jakichkolwiek pytań, prosimy o kontakt.
        </p>
        
        <p>
            Pozdrawiamy,<br>
            <strong>{{ config('app.name') }}</strong>
        </p>
    </div>
    
    <div class="footer">
        <p>
            <strong>Informacja o ochronie danych osobowych (RODO):</strong><br>
            Ten email oraz załączniki mogą zawierać poufne informacje chronione przepisami RODO.
            Jeśli nie jesteś zamierzonym odbiorcą, prosimy o niezwłoczne usunięcie tej wiadomości
            oraz poinformowanie nadawcy o błędnym dostarczeniu.
        </p>
        <p style="margin-top: 15px;">
            © {{ date('Y') }} {{ config('app.name') }}. Wszystkie prawa zastrzeżone.
        </p>
    </div>
</body>
</html>