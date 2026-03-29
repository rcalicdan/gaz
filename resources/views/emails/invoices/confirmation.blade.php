@extends('emails.layouts.master')

@section('title', 'Potwierdzenie Wystawienia Faktury')
@section('header', 'Potwierdzenie Faktury')

@section('content')
    <p style="margin: 0 0 20px 0; font-size: 16px; color: #15491A; line-height: 1.6;">
        Dzień dobry,
    </p>

    <p style="margin: 0 0 25px 0; font-size: 16px; color: #444444; line-height: 1.6;">
        Informujemy, że w systemie <strong>Olejos</strong> została wystawiona faktura w Państwa imieniu za wykonany odbiór
        odpadów. Dokument został pomyślnie przetworzony przez system KSeF.
    </p>

    <table border="0" cellpadding="0" cellspacing="0" width="100%"
        style="background-color: #F8FCF8; border: 1px solid #CFF4C8; border-radius: 6px; margin-bottom: 30px;">
        <tbody>
            <tr>
                <td style="padding: 20px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="40%"
                                style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                Numer faktury:</td>
                            <td width="60%"
                                style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; text-align: right;">
                                {{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                Numer KSeF:</td>
                            <td width="60%"
                                style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; font-weight: 600; text-align: right;">
                                {{ $invoice->ksef_reference_number }}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #2E7D32; font-size: 14px; font-weight: 600;">
                                Data wystawienia:</td>
                            <td
                                style="padding: 10px 0; border-bottom: 1px solid #DFF4DD; color: #15491A; font-size: 14px; text-align: right;">
                                {{ $invoice->issue_date->format('d.m.Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 0; color: #2E7D32; font-size: 14px; font-weight: 600;">Kwota do
                                zapłaty:</td>
                            <td
                                style="padding: 10px 0; color: #15491A; font-size: 14px; font-weight: 700; text-align: right;">
                                {{ number_format($invoice->gross_amount, 2, ',', ' ') }}
                                {{ $invoice->client->currency ?? 'PLN' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <p style="margin: 0 0 25px 0; font-size: 15px; color: #666666; line-height: 1.6;">
        Dokument jest dostępny do wglądu w Państwa panelu KSeF przy użyciu powyższego numeru KSeF.
    </p>
@endsection
