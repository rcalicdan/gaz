<?php

namespace App\Services;

use App\Models\Pickup;
use App\Enums\PickupStatus;
use Illuminate\Support\Facades\DB;

class ClientAppWasteService
{
    public function getWasteRecords(int $clientId, array $filters)
    {
        $query = Pickup::with(['wasteType', 'kpoDocument'])
            ->where('client_id', $clientId)
            ->where('status', PickupStatus::COMPLETED);

        if (!empty($filters['date_from'])) {
            $query->whereDate('actual_pickup_time', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('actual_pickup_time', '<=', $filters['date_to']);
        }

        if (!empty($filters['waste_type_id'])) {
            $query->where('waste_type_id', $filters['waste_type_id']);
        }

        return $query->orderBy('actual_pickup_time', 'desc')->get();
    }

    public function getWasteStatistics(int $clientId, array $filters): array
    {
        $query = Pickup::where('client_id', $clientId)
            ->where('status', PickupStatus::COMPLETED)
            ->whereYear('actual_pickup_time', $filters['year']);

        if (!empty($filters['month'])) {
            $query->whereMonth('actual_pickup_time', $filters['month']);
        }

        $pickups = $query->with('wasteType')->get();

        $groupedStats = $pickups->groupBy('waste_type_id')->map(function ($group) {
            $first = $group->first();
            return [
                'waste_type_id' => $first->waste_type_id,
                'waste_code' => $first->wasteType->code ?? null,
                'waste_name' => $first->wasteType->name ?? null,
                'total_quantity_kg' => $group->sum('waste_quantity'),
                'pickup_count' => $group->count(),
            ];
        })->values()->toArray();

        $totalQuantity = array_sum(array_column($groupedStats, 'total_quantity_kg'));
        $totalPickups = array_sum(array_column($groupedStats, 'pickup_count'));

        return [
            'year' => (int) $filters['year'],
            'month' => isset($filters['month']) ? (int) $filters['month'] : null,
            'summary' => [
                'total_quantity_kg' => $totalQuantity,
                'total_pickups' => $totalPickups,
            ],
            'breakdown' => $groupedStats
        ];
    }

    public function exportWasteRecords(int $clientId, array $filters, string $format)
    {
        $records = $this->getWasteRecords($clientId, $filters);
        $datePrefix = date('Y-m-d');

        if ($format === 'csv') {
            return $this->generateCsv($records, "bdo_zestawienie_{$datePrefix}.csv");
        }

        return $this->generatePdf($records, "bdo_zestawienie_{$datePrefix}.pdf");
    }

    protected function generateCsv($records, string $filename)
    {
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, ['Data odbioru', 'Kod odpadu', 'Nazwa odpadu', 'Waga (kg)', 'Nr KPO']);

        foreach ($records as $record) {
            fputcsv($handle, [
                $record->actual_pickup_time ? $record->actual_pickup_time->format('Y-m-d H:i') : '',
                $record->wasteType->code ?? '',
                $record->wasteType->name ?? '',
                $record->waste_quantity ?? '0',
                $record->kpoDocument->kpo_number ?? 'Brak'
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    protected function generatePdf($records, string $filename)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(config('app.name'));
        $pdf->SetTitle('Zestawienie Odbioru Odpadów');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->Cell(0, 10, 'Zestawienie Odbioru Odpadów (BDO)', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('dejavusans', '', 9);

        $html = '<table border="1" cellpadding="5">
                    <tr style="background-color:#f2f2f2; font-weight:bold;">
                        <th width="20%">Data odbioru</th>
                        <th width="15%">Kod odpadu</th>
                        <th width="35%">Nazwa odpadu</th>
                        <th width="15%">Waga (kg)</th>
                        <th width="15%">Nr KPO</th>
                    </tr>';

        foreach ($records as $record) {
            $date = $record->actual_pickup_time ? $record->actual_pickup_time->format('Y-m-d H:i') : '';
            $code = htmlspecialchars($record->wasteType->code ?? '', ENT_QUOTES, 'UTF-8');
            $name = htmlspecialchars($record->wasteType->name ?? '', ENT_QUOTES, 'UTF-8');
            $weight = number_format((float) ($record->waste_quantity ?? 0), 2, ',', ' ');
            $kpo = htmlspecialchars($record->kpoDocument->kpo_number ?? 'Brak', ENT_QUOTES, 'UTF-8');

            $html .= "<tr>
                        <td>{$date}</td>
                        <td>{$code}</td>
                        <td>{$name}</td>
                        <td>{$weight}</td>
                        <td>{$kpo}</td>
                      </tr>";
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdfContent = $pdf->Output('', 'S');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
