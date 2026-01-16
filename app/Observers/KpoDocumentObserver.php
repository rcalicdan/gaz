<?php

namespace App\Observers;

use App\Models\KpoDocument;
use App\Services\KpoPdfService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KpoDocumentObserver
{
    public function updated(KpoDocument $kpoDocument): void
    {
        $fieldsToWatch = ['waste_code', 'quantity', 'additional_notes'];
        
        if ($kpoDocument->isDirty($fieldsToWatch)) {
            try {
                Log::info('KPO document fields changed, regenerating PDF', [
                    'kpo_id' => $kpoDocument->id,
                    'changed_fields' => array_keys($kpoDocument->getChanges()),
                ]);
                
                app(KpoPdfService::class)->generateKpoDocument($kpoDocument);
            } catch (\Exception $e) {
                Log::error('Failed to auto-regenerate PDF after update', [
                    'kpo_id' => $kpoDocument->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function deleting(KpoDocument $kpoDocument): void
    {
        if ($kpoDocument->pdf_path && Storage::exists($kpoDocument->pdf_path)) {
            Storage::delete($kpoDocument->pdf_path);
            
            Log::info('Deleted PDF file for KPO document', [
                'kpo_id' => $kpoDocument->id,
                'pdf_path' => $kpoDocument->pdf_path,
            ]);
        }
    }

    public function forceDeleted(KpoDocument $kpoDocument): void
    {
        if ($kpoDocument->pdf_path && Storage::exists($kpoDocument->pdf_path)) {
            Storage::delete($kpoDocument->pdf_path);
        }
    }
}