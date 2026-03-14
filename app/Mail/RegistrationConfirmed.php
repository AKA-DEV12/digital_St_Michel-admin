<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistrationConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $uuid;
    public $participants;
    public $registration;

    /**
     * Create a new message instance.
     */
    public function __construct($uuid)
    {
        $this->uuid = $uuid;
        $this->participants = Registration::where('uuid', $uuid)->with('registrationActivity')->get();
        $this->registration = $this->participants->first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation d\'Inscription - ' . ($this->registration->registrationActivity->title ?? 'Activité'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registrations.confirmed',
            with: [
                'activityTitle' => $this->registration->registrationActivity->title ?? 'N/A',
                'activityDate' => \Carbon\Carbon::parse($this->registration->registrationActivity->date)->format('d/m/Y'),
                'registrationOption' => $this->registration->option,
                'participants' => $this->participants,
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
        $attachments = [];

        foreach ($this->participants as $participant) {
            // L'ID qui sera encodé dans le QR Code pour le scan
            $qrData = $participant->id;

            // Generate QR code as base64
            $qrCodeBase64 = base64_encode(QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate($qrData));

            // Data for the receipt
            $data = [
                'activityTitle' => $this->registration->registrationActivity->title ?? 'Activité',
                'registrationOption' => $participant->option,
                'participantName' => $participant->full_name,
                'groupName' => $participant->group_name,
                'registrationDate' => $participant->created_at ? \Carbon\Carbon::parse($participant->created_at)->locale('fr')->translatedFormat('d F Y') : \Carbon\Carbon::now()->locale('fr')->translatedFormat('d F Y'),
                'uuid' => $this->uuid,
                'qrCode' => $qrCodeBase64,
            ];

            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.registrations.receipt', $data);
            $pdfContent = $pdf->output();

            $attachments[] = Attachment::fromData(fn() => $pdfContent, "Recu_Inscription_{$participant->full_name}.pdf")
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
