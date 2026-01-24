<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KPO Document</title>
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }

        body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #E8F9E6; }
        
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; margin: auto !important; }
            .stack-column { display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important; }
            .mobile-padding { padding-left: 20px !important; padding-right: 20px !important; }
            .mobile-center { text-align: center !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #E8F9E6; font-family: 'Segoe UI', Helvetica, Arial, sans-serif;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #E8F9E6;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                
                <table class="email-container" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    
                    <tr>
                        <td align="center" style="background-color: #7BC143; padding: 35px 20px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
                                Dokument KPO
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td class="mobile-padding" style="padding: 40px 40px 30px 40px;">
                            
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #15491A; line-height: 1.6;">
                                Dzień dobry,
                            </p>

                            @if ($customMessage)
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 25px;">
                                    <tr>
                                        <td style="background-color: #F0F9F1; border-left: 5px solid #4CAF50; padding: 20px; border-radius: 4px;">
                                            <p style="margin: 0; color: #1F6B26; font-style: italic; font-size: 15px; line-height: 1.6;">
                                                {!! nl2br(e($customMessage)) !!}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin: 0 0 25px 0; font-size: 16px; color: #444444; line-height: 1.6;">
                                W załączeniu przesyłamy dokument <strong>KPO</strong> (Oświadczenie dotyczące przekazania odpadów).
                            </p>

                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8FCF8; border: 1px solid #CFF4C8; border-radius: 6px; margin-bottom: 30px;">
                                <tbody>
                                    <tr>
                                        <td style="padding: 20px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                
                                                <tr>
                                                    <td width="40%" style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                                        Numer KPO:
                                                    </td>
                                                    <td width="60%" style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; font-weight: 400; text-align: right;">
                                                        {{ $kpoDocument->kpo_number }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                                        Data utworzenia:
                                                    </td>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; font-weight: 400; text-align: right;">
                                                        {{ $kpoDocument->created_at->format('d.m.Y H:i') }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                                        Kod odpadów:
                                                    </td>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; font-weight: 400; text-align: right;">
                                                        {{ $kpoDocument->waste_code }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                                        Ilość:
                                                    </td>
                                                    <td style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; font-weight: 700; text-align: right;">
                                                        {{ number_format($kpoDocument->quantity, 2) }} kg
                                                    </td>
                                                </tr>

                                                @if ($kpoDocument->client)
                                                <tr>
                                                    <td style="padding: 10px 0; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                                        Klient:
                                                    </td>
                                                    <td style="padding: 10px 0; color: #15491A; font-size: 14px; font-weight: 400; text-align: right;">
                                                        {{ $kpoDocument->client->company_name }}
                                                    </td>
                                                </tr>
                                                @endif
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p style="margin: 0 0 25px 0; font-size: 15px; color: #666666; line-height: 1.6;">
                                Dokument KPO znajduje się w załączniku PDF. Prosimy o zachowanie dokumentu dla celów ewidencyjnych.
                            </p>

                            <p style="margin: 0; font-size: 16px; color: #15491A; line-height: 1.6;">
                                Pozdrawiamy,<br>
                                <strong style="color: #7BC143;">{{ config('app.name') }}</strong>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #F0F9F1; padding: 30px 40px; border-top: 1px solid #CFF4C8; text-align: center;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #2E7D32; line-height: 1.5;">
                                <strong>Informacja o ochronie danych osobowych (RODO):</strong><br>
                                Ten email oraz załączniki mogą zawierać poufne informacje chronione przepisami RODO.
                                Jeśli nie jesteś zamierzonym odbiorcą, prosimy o niezwłoczne usunięcie tej wiadomości
                                oraz poinformowanie nadawcy o błędnym dostarczeniu.
                            </p>
                            <p style="margin: 15px 0 0 0; font-size: 12px; color: #4BAE2E;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. Wszystkie prawa zastrzeżone.
                            </p>
                        </td>
                    </tr>

                </table>
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>