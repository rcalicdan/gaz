<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style type="text/css">
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #E8F9E6;
        }

        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            .stack-column {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }

            .mobile-padding {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #E8F9E6; font-family: 'Segoe UI', Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #E8F9E6;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table class="email-container" border="0" cellpadding="0" cellspacing="0" width="600"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #7BC143; padding: 35px 20px;">
                            <h1
                                style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">
                                @yield('header')
                            </h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="mobile-padding" style="padding: 40px 40px 30px 40px;">
                            @yield('content')

                            <p style="margin: 25px 0 0 0; font-size: 16px; color: #15491A; line-height: 1.6;">
                                Pozdrawiamy,<br>
                                <strong style="color: #7BC143;">{{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #F0F9F1; padding: 30px 40px; border-top: 1px solid #CFF4C8; text-align: center;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #2E7D32; line-height: 1.5;">
                                <strong>Informacja o ochronie danych osobowych (RODO):</strong><br>
                                Ten email oraz załączniki mogą zawierać poufne informacje chronione przepisami RODO.
                            </p>
                            <p style="margin: 15px 0 0 0; font-size: 12px; color: #4BAE2E;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. Wszystkie prawa zastrzeżone.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
