<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Paiement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    public function build()
    {
        return $this->subject('Validation de votre commande')
            ->from('mathieu.servonnet@etu.univ-smb.fr', 's231_Uber')
            ->view('auth/Paiment') ;
    }

    /**
     * Get the message envelope.
     */

}





