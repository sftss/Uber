<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SendGrid\Mail\Mail;

class MailController extends Controller
{
    public function sendVerificationEmail($toEmail, $userName)
    {
        $email = new Mail();
        $email->setFrom("ton-email@example.com", "Nom du Site");
        $email->setSubject("Vérification de votre compte");
        $email->addTo($toEmail, $userName);
        $email->addContent(
            "text/html",
            "<strong>Bienvenue, $userName !</strong><br>
             Cliquez sur le lien suivant pour vérifier votre compte : 
             <a href='https://ton-site.com/verification?email=$toEmail'>Vérifier mon compte</a>"
        );

        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
            return response()->json(['message' => 'Email envoyé avec succès !'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
