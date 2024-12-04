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
            'mail_client' => 'required|email|max:70|unique:client,mail_client', // Utilisez 'mail_client' au lieu de 'email'
            'mdp_client' => 'required|string|min:8|confirmed',
        ]);

        // Création du client dans la base de données
        $client = new Client();
        $client->PRENOM_CP = $validatedData['prenom_cp'];
        $client->NOM_CP = $validatedData['nom_cp'];
        $client->mail_client = $validatedData['email'];
        $client->MDP_CLIENT = Hash::make($validatedData['mdp_client']); // Hash du mot de passe
        $client->TEL_CLIENT = $validatedData['tel_client'] ?? null;
        $client->DATE_NAISSANCE_CP = $validatedData['date_naissance_cp'] ?? null;
        $client->SEXE_CP = $validatedData['sexe_cp'] ?? null;
        $client->EST_PARTICULIER = $validatedData['est_particulier'] ?? null;
        $client->NEWSLETTER = $validatedData['newsletter'] ?? null;
        // Assurez-vous que l'ID_SD et NUM_SIRET sont ajoutés selon votre logique
        // Par exemple, vous pouvez définir un ID_SD par défaut ou le calculer
        $client->ID_SD = 1; // Exemple de valeur par défaut
        $client->NUM_SIRET = null; // Vous pouvez ajouter un calcul ou une valeur par défaut ici
        $client->PHOTO = null; // Si vous n'utilisez pas cette colonne pour l'instant

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
