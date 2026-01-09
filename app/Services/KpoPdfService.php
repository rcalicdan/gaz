<?php

namespace App\Services;

use App\Models\KpoDocument;
use App\Models\Pickup;
use TCPDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KpoPdfService
{
    protected TCPDF $pdf;
    
    public function __construct()
    {
        $this->initializePdf();
    }

    protected function initializePdf(): void
    {
        $this->pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        
        $this->pdf->SetCreator(config('app.name'));
        $this->pdf->SetAuthor(config('app.name'));
        $this->pdf->SetTitle('KPO Document');
        $this->pdf->setPrintHeader(false);
        $this->pdf->setPrintFooter(false);
        $this->pdf->SetMargins(15, 15, 15);
        $this->pdf->SetAutoPageBreak(true, 15);
        $this->pdf->SetFont('helvetica', '', 10);
    }

    public function generateKpoDocument(KpoDocument $kpoDocument): string
    {
        try {
            $this->pdf->AddPage();
            
            $pickup = $kpoDocument->pickup;
            $client = $kpoDocument->client;
            
            $this->addHeader($kpoDocument);
            $this->addSenderSection($pickup);
            $this->addRecipientSection($client);
            $this->addWasteDetails($kpoDocument, $pickup);
            $this->addTermsSection();
            
            $filename = $this->generateFilename($kpoDocument);
            
            $pdfContent = $this->pdf->Output('', 'S');
            $path = 'kpo-documents/' . $filename;
            Storage::put($path, $pdfContent);
            
            $kpoDocument->update([
                'pdf_url' => Storage::url($path)
            ]);
            
            Log::info('KPO PDF generated successfully', [
                'kpo_id' => $kpoDocument->id,
                'filename' => $filename
            ]);
            
            return $path;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate KPO PDF', [
                'kpo_id' => $kpoDocument->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function addHeader(KpoDocument $kpoDocument): void
    {
        $this->pdf->SetFillColor(52, 73, 94);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', 'B', 16);
        $this->pdf->Cell(0, 12, 'Oświadczenie dotyczące przekazania odpadów', 0, 1, 'C', true);
        
        $this->pdf->Ln(3);
        
        $this->pdf->SetFillColor(236, 240, 241);
        $this->pdf->SetTextColor(44, 62, 80);
        $this->pdf->SetFont('helvetica', '', 9);
        
        $html = '
        <table cellpadding="5" style="border: 1px solid #bdc3c7;">
            <tr>
                <td style="background-color: #ecf0f1; width: 50%; border-right: 1px solid #bdc3c7;">
                    <strong>Nr ODPQ:</strong><br>
                    ' . htmlspecialchars($kpoDocument->kpo_number ?? 'N/A') . '
                </td>
                <td style="background-color: #ecf0f1; width: 50%;">
                    <strong>z dnia:</strong><br>
                    ' . $kpoDocument->created_at->format('d.m.Y') . '
                </td>
            </tr>
        </table>';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        $this->pdf->Ln(5);
    }

    protected function addSenderSection(Pickup $pickup): void
    {
        $this->pdf->SetFillColor(41, 128, 185);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(90, 8, 'Przekazujący odpady', 0, 0, 'L', true);
        $this->pdf->Ln(8);
        
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetTextColor(44, 62, 80);
        $this->pdf->SetFont('helvetica', '', 9);
        
        $companyName = config('company.name', 'Your Company Name');
        $companyAddress = config('company.address', 'Company Address');
        $companyNip = config('company.nip', 'NIP: XXXXXXXXXX');
        
        $html = '
        <table cellpadding="5" style="border: 1px solid #bdc3c7; background-color: #ffffff;">
            <tr>
                <td style="border-bottom: 1px solid #ecf0f1; padding: 8px;">
                    <strong style="color: #7f8c8d;">Nazwa Firmy:</strong><br>
                    <span style="font-size: 10pt;">' . htmlspecialchars($companyName) . '</span>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #ecf0f1; padding: 8px;">
                    <strong style="color: #7f8c8d;">Adres:</strong><br>
                    <span style="font-size: 10pt;">' . htmlspecialchars($companyAddress) . '</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px;">
                    <strong style="color: #7f8c8d;">NIP:</strong><br>
                    <span style="font-size: 10pt;">' . htmlspecialchars($companyNip) . '</span>
                </td>
            </tr>
        </table>';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        $this->pdf->Ln(5);
    }

    protected function addRecipientSection($client): void
    {
        $this->pdf->SetFillColor(41, 128, 185);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(90, 8, 'PIECZĄTKA FIRMY', 0, 0, 'L', true);
        $this->pdf->Ln(8);
        
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetTextColor(44, 62, 80);
        $this->pdf->SetFont('helvetica', '', 9);
        
        $fullAddress = $client->street_name . ' ' . $client->street_number . ', ' . 
                       $client->zip_code . ' ' . $client->city;
        
        $html = '
        <table cellpadding="8" style="border: 1px solid #bdc3c7; background-color: #ffffff;">
            <tr>
                <td style="height: 80px; text-align: center; vertical-align: middle; color: #95a5a6;">
                    <em>Miejsce na pieczątkę firmy odbierającej odpady</em>
                </td>
            </tr>
        </table>
        
        <table cellpadding="5" style="border: 1px solid #bdc3c7; background-color: #ffffff; margin-top: 5px;">
            <tr>
                <td style="padding: 8px;">
                    <strong style="color: #7f8c8d;">PODPIS PRZEKAZUJĄCEGO:</strong><br>
                    <div style="height: 30px; border-bottom: 1px solid #bdc3c7; margin-top: 5px;"></div>
                </td>
            </tr>
        </table>';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        $this->pdf->Ln(5);
    }

    protected function addWasteDetails(KpoDocument $kpoDocument, Pickup $pickup): void
    {
        $this->pdf->SetFillColor(46, 204, 113);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(0, 8, 'PRZYJMUJĄCY ODPADY:', 0, 1, 'L', true);
        $this->pdf->Ln(2);
        
        $this->pdf->SetFillColor(255, 255, 255);
        $this->pdf->SetTextColor(44, 62, 80);
        $this->pdf->SetFont('helvetica', '', 9);
        
        $wasteTypeName = $pickup->wasteType->name ?? 'N/A';
        $wasteCode = $kpoDocument->waste_code ?? $pickup->wasteType->code ?? 'N/A';
        
        $html = '
        <table cellpadding="5" style="border: 1px solid #bdc3c7; background-color: #ffffff;">
            <tr>
                <td style="width: 50%; border-right: 1px solid #ecf0f1; border-bottom: 1px solid #ecf0f1; padding: 8px;">
                    <strong style="color: #7f8c8d;">ROD ODPADÓW:</strong><br>
                    <span style="font-size: 10pt;">' . htmlspecialchars($wasteCode) . '</span>
                </td>
                <td style="width: 50%; border-bottom: 1px solid #ecf0f1; padding: 8px;">
                    <strong style="color: #7f8c8d;">Kod z dnia:</strong><br>
                    <span style="font-size: 10pt;">30 01 25 oleje i tłuszcze jadalne</span>
                </td>
            </tr>
            <tr>
                <td style="border-right: 1px solid #ecf0f1; border-bottom: 1px solid #ecf0f1; padding: 8px;">
                    <strong style="color: #7f8c8d;">Nr REJESTRACYJNY POJAZDU:</strong><br>
                    <span style="font-size: 10pt;">' . htmlspecialchars($pickup->driver->user->full_name ?? 'N/A') . '</span>
                </td>
                <td style="border-bottom: 1px solid #ecf0f1; padding: 8px;">
                    <strong style="color: #7f8c8d;">Numer rejestracyjny pojazdu:</strong><br>
                    <span style="font-size: 10pt;">' . htmlspecialchars($pickup->certificate_number ?? 'N/A') . '</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 8px;">
                    <strong style="color: #7f8c8d;">MASA PRZEJĘTYCH ODPADÓW:</strong><br>
                    <span style="font-size: 12pt; font-weight: bold;">' . number_format($kpoDocument->quantity, 2) . ' KG</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 8px; background-color: #ecf0f1;">
                    <strong style="color: #7f8c8d;">DATA PRZEJĘCIA I PODPIS OSOBY PRZYJMUJĄCEJ:</strong><br>
                    <div style="height: 30px; margin-top: 5px;"></div>
                </td>
            </tr>
        </table>';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        $this->pdf->Ln(5);
    }

    protected function addTermsSection(): void
    {
        $this->pdf->SetFont('helvetica', '', 7);
        $this->pdf->SetTextColor(127, 140, 141);
        
        $terms = 'Przekazuję odpady identyfikacji do przejęcia, opakowania, i przewozu zgodnie z przepisami ustawy z dnia 14 grudnia 2012 r. o odpadach (Dz.U. z 2013 r. poz. 21).';
        
        $html = '<div style="border-top: 1px solid #bdc3c7; padding-top: 10px; margin-top: 10px; color: #7f8c8d; font-size: 7pt; text-align: justify;">' . 
                htmlspecialchars($terms) . 
                '</div>';
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
    }

    protected function generateFilename(KpoDocument $kpoDocument): string
    {
        $date = $kpoDocument->created_at->format('Y-m-d');
        $kpoNumber = $kpoDocument->kpo_number ?? $kpoDocument->id;
        
        return "KPO_{$kpoNumber}_{$date}.pdf";
    }

    public function downloadPdf(KpoDocument $kpoDocument): string
    {
        $this->generateKpoDocument($kpoDocument);
        return $this->pdf->Output($this->generateFilename($kpoDocument), 'D');
    }

    public function previewPdf(KpoDocument $kpoDocument): string
    {
        $this->generateKpoDocument($kpoDocument);
        return $this->pdf->Output($this->generateFilename($kpoDocument), 'I');
    }

    public function getPdfContent(KpoDocument $kpoDocument): string
    {
        $this->generateKpoDocument($kpoDocument);
        return $this->pdf->Output('', 'S');
    }
}