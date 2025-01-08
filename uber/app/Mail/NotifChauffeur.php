<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifChauffeur extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct()
    {

    }

    public function build()
    {
        return $this->subject('Nouvelle proposition de course !')
            ->from('mathieu.servonnet@etu.univ-smb.fr', 's231_Uber')
            ->view('chauffeur/notification') ;
    }
}