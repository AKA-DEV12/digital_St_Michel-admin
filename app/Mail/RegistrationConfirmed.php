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

            // Generate QR code as PNG (base64 string then decoded)
            // Note: simple-qrcode supports generating PNG if imagick or gd is installed
            $qrCodeImage = (string) QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate($qrData);

            $attachments[] = Attachment::fromData(fn() => $qrCodeImage, "Ticket_{$participant->full_name}.png")
                ->withMime('image/png');
        }

        return $attachments;
    }
}
