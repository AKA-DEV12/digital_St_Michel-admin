<?php

namespace App\Mail;

use App\Models\MassRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MassRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $massRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(MassRequest $massRequest)
    {
        $this->massRequest = $massRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle Demande de Messe - ' . $this->massRequest->name1,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mass-requests.submitted',
            with: [
                'massRequest' => $this->massRequest,
                'timeSlotsDisplay' => is_array($this->massRequest->time_slots) 
                    ? implode(', ', $this->massRequest->time_slots)
                    : $this->massRequest->time_slots,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
