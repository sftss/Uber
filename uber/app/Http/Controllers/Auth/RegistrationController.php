<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\MailController; // Import du MailController
use Illuminate\Support\Str;
use App\Mail\ConfirmationEmail;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'prenom_cp' => 'required|string|max:20',
            'nom_cp' => 'required|string|max:30',
            'mail_client' => [
                'required',
                'email',
                'max:70',
                'unique:client,mail_client', 
            ],
            'mdp_client' => 'required|string|min:8|confirmed',
            'tel_client' => 'nullable|string|max:15',
        ], [
            'mail_client.unique' => 'Cette adresse email est déjà utilisée. Veuillez en choisir une autre.',
        ]);


        $telClient = $validatedData['tel_client'] ?? null;
        if ($telClient) {
            $telClient = preg_replace('/\D/', '', $telClient);
            if (str_starts_with($telClient, '0')) {
                $telClient = substr($telClient, 1);
            }
        }


        $client = new Client();
        $client->PRENOM_CP = $validatedData['prenom_cp'];
        $client->NOM_CP = $validatedData['nom_cp'];
        $client->mail_client = $validatedData['mail_client'];
        $client->MDP_CLIENT = Hash::make($validatedData['mdp_client']);
        $client->TEL_CLIENT = $telClient;
        $client->DATE_NAISSANCE_CP = $request->input('date_naissance_cp', null);
        $client->SEXE_CP = $request->input('sexe_cp', null);

        //checkboxes
        $client->EST_PARTICULIER = $request->has('est_particulier') ? true : false;
        $client->NEWSLETTER = $request->has('newsletter') ? true : false;


        $client->ID_SD = 1;
        $client->NUM_SIRET = null;
        $client->PHOTO = null;

        $client->save(); 

        // Génération d'un code ou d'un lien de vérification
        $verificationLink = route('verify.email', ['code' => Str::random(32)]);

        $mailController = new MailController();
        $mailController->sendVerificationEmail($client->mail_client, $client->PRENOM_CP);

        return redirect()->route('home')->with('success', 'Inscription réussie. Veuillez vérifier votre email pour confirmer votre compte.');
    }

}
