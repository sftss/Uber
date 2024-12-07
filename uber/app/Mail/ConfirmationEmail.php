<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;

    /**
     * Create a new message instance.
     *
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Votre code de vérification")
                    ->view('emails.verification_email')
                    ->with([
                        'code' => $this->client->code_verif,
                        'prenom' => $this->client->prenom_cp,
                        'nom' => $this->client->nom_cp,
                    ]);
    }
}
