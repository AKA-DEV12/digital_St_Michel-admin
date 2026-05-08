<?php

namespace App\Mail;

use App\Models\MassRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MassRequestConfirmed extends Mailable
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
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre demande de messe - Saint Michel')
                    ->view('emails.mass_request_confirmed');
    }
}
