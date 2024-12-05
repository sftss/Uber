<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;

    public function __construct($code)
    {
        $this->verificationCode = $code;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre compte')
                    ->view('emails.confirmation')
                    ->with(['code' => $this->verificationCode]);
    }
}