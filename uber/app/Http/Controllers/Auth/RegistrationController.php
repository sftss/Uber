<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                'unique:client,mail_client', // Contrainte d'unicité
            ],
            'mdp_client' => 'required|string|min:8|confirmed',
            'tel_client' => 'nullable|string|max:15',
        ], [
            // Messages d'erreur personnalisés
            'mail_client.unique' => 'Cette adresse email est déjà utilisée. Veuillez en choisir une autre.',
        ]);

        // Nettoyage du numéro de téléphone
        $telClient = $validatedData['tel_client'] ?? null;
        if ($telClient) {
            $telClient = preg_replace('/\D/', '', $telClient);
            if (str_starts_with($telClient, '0')) {
                $telClient = substr($telClient, 1);
            }
        }

        // Création du client dans la base de données
        $client = new Client();
        $client->PRENOM_CP = $validatedData['prenom_cp'];
        $client->NOM_CP = $validatedData['nom_cp'];
        $client->mail_client = $validatedData['mail_client'];
        $client->MDP_CLIENT = Hash::make($validatedData['mdp_client']);
        $client->TEL_CLIENT = $telClient;
        $client->DATE_NAISSANCE_CP = $request->input('date_naissance_cp', null);
        $client->SEXE_CP = $request->input('sexe_cp', null);

        // Gestion des checkboxes
        $client->EST_PARTICULIER = $request->has('est_particulier') ? true : false;
        $client->NEWSLETTER = $request->has('newsletter') ? true : false;

        // Valeurs par défaut pour les champs restants
        $client->ID_SD = 1;
        $client->NUM_SIRET = null;
        $client->PHOTO = null;

        $client->save(); // Enregistrer le client dans la base de données

        // Rediriger vers la page d'accueil ou une autre page après l'inscription
        return redirect()->route('home')->with('success', 'Inscription réussie');
    }





    public function confirmEmail($code)
    {
        // Vérifier le code de confirmation
        $user = User::where('confirmation_code', $code)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Code de confirmation invalide.');
        }

        // Confirmer l'email
        $user->email_verified_at = now();
        $user->confirmation_code = null;  // Supprimer le code
        $user->save();

        return redirect()->route('login')->with('status', 'Votre email a été confirmé.');
    }
}
