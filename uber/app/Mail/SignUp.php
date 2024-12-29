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

    public $code_verif;

    public function __construct($code_verif)
    {
        $this->code_verif = $code_verif;
    }

    public function build()
    {
        return $this->subject('Vérification de votre compte Uber pour la SAE de Mr.Colin (le GOAT💪).')
            ->from('mathieu.servonnet@etu.univ-smb.fr', 's231_Uber')
            ->view('auth/SignUpView') 
            ->with(['code_verif' => $this->code_verif]);
    }
}