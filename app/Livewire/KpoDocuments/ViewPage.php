<?php

namespace App\Livewire\KpoDocuments;

use App\Models\KpoDocument;
use App\Services\KpoEmailService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class ViewPage extends Component
{
    public KpoDocument $kpoDocument;
    public $customEmail = '';
    public $customMessage = '';

    public function mount(KpoDocument $kpoDocument)
    {
        $this->kpoDocument = $kpoDocument->load([
            'client', 
            'pickup.wasteType', 
            'emailLogs' => fn($query) => $query->orderBy('created_at', 'desc')
        ]);
    }

    public function regeneratePdf()
    {
        try {
            $this->kpoDocument->regeneratePdf();
            $this->dispatch('show-message', [
                'type' => 'success', 
                'message' => __('PDF successfully regenerated.')
            ]);
            $this->kpoDocument->refresh();
        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'type' => 'error', 
                'message' => __('Failed to regenerate PDF: ') . $e->getMessage()
            ]);
        }
    }

    public function downloadPdf()
    {
        if (!$this->kpoDocument->hasPdf()) {
            $this->dispatch('show-message', [
                'type' => 'error', 
                'message' => __('PDF file not found.')
            ]);
            return;
        }
        
        return Storage::download(
            $this->kpoDocument->pdf_path, 
            "KPO_{$this->kpoDocument->kpo_number}.pdf"
        );
    }

    public function sendEmail(KpoEmailService $emailService)
    {
        if ($emailService->sendToClient($this->kpoDocument)) {
            $this->dispatch('show-message', [
                'type' => 'success', 
                'message' => __('Email sent to client successfully.')
            ]);
        } else {
            $this->dispatch('show-message', [
                'type' => 'error', 
                'message' => __('Failed to send email. Check the activity log.')
            ]);
        }
        
        $this->kpoDocument->refresh()->load('emailLogs');
    }

    public function sendCustomEmail(KpoEmailService $emailService)
    {
        $this->validate([
            'customEmail' => 'required|email',
            'customMessage' => 'nullable|string|max:500'
        ]);

        if ($emailService->sendToCustomEmail($this->kpoDocument, $this->customEmail, $this->customMessage)) {
            $this->dispatch('show-message', [
                'type' => 'success', 
                'message' => __('Email sent to ' . $this->customEmail)
            ]);
            $this->customEmail = '';
            $this->customMessage = '';
            
            $this->dispatch('close-custom-email-modal');
        } else {
            $this->dispatch('show-message', [
                'type' => 'error', 
                'message' => __('Failed to send email. Check the activity log.')
            ]);
        }
        
        $this->kpoDocument->refresh()->load('emailLogs');
    }

    public function render()
    {
        return view('livewire.kpo-documents.view-page');
    }
}