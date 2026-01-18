<?php

namespace App\Mail;

use App\Models\KpoDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KpoDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public KpoDocument $kpoDocument,
        public string $recipientEmail,
        public ?string $customMessage = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: 'KPO Document - ' . $this->kpoDocument->kpo_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.kpo-document',
            with: [
                'kpoDocument' => $this->kpoDocument,
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        if (!$this->kpoDocument->hasPdf()) {
            return [];
        }

        $filename = "KPO_{$this->kpoDocument->kpo_number}.pdf";

        return [
            Attachment::fromStorage($this->kpoDocument->pdf_path)
                ->as($filename)
                ->withMime('application/pdf'),
        ];
    }
}