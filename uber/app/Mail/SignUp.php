<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignUp extends Mailable
{
    use Queueable, SerializesModels;

    public $code_verif; // Déclarez une propriété publique

    public function __construct($code_verif)
    {
        $this->code_verif = $code_verif; // Initialisez la propriété
    }

    public function build()
    {
        return $this->subject('Berkan Mange mon paf !')
            ->from('mathieu.servonnet@etu.univ-smb.fr', 's231_Uber')
            ->view('SignUpView') // Utilisez la vue Blade
            ->with(['code_verif' => $this->code_verif]); // Passez le code_verif à la vue
    }
}