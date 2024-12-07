<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SimpleMail extends Mailable
{
    public $subject;
    public $message;

    // Le constructeur prend un message en paramètre
    public function __construct($message)
    {
        $this->message = $message;  // Assurez-vous que $message est bien une chaîne
        $this->subject = "Sujet de l'email";  // Vous pouvez personnaliser le sujet
    }

    public function build()
    {
        // Retourner le mail avec la vue et le message passé à la vue
        return $this->subject($this->subject)
                    ->view('emails.simple')  // Vue utilisée pour l'email
                    ->with([
                        'message' => $this->message,  // Assurez-vous que c'est bien une chaîne
                    ]);
    }
}
