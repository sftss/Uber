<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\ConfirmationEmail; // Assurez-vous que le mail est bien importé
use App\Models\Secteur;

class RegisterController extends Controller
{
    // Afficher le formulaire d'inscription
    public function showRegistrationForm()
    {
        $secteurs = Secteur::all();
        return view('auth.register', compact('secteurs'));
    }

    // Enregistrer un client
    public function register(Request $request)
{
    $validatedData = $request->validate([
        'prenom_cp' => 'required|string|max:20',
        'nom_cp' => 'required|string|max:30',
        'mail_client' => 'required|email|max:70|unique:client,mail_client',
        'sexe_cp' => 'required|in:H,F',
        'mdp_client' => 'required|string|min:8|confirmed',
        'tel_client' => 'nullable|string|max:15',
        'est_particulier' => 'required|boolean',
        'num_siret' => 'required_if:est_particulier,0|nullable|string|max:14',
        'secteur_activite' => 'required_if:est_particulier,0|exists:secteur_d_activite,id_sd',
    ], [
        'mdp_client.confirmed' => 'Les mots de passe ne correspondent pas.',
        'mail_client.unique' => 'Cette adresse email est déjà utilisée.',
        'sexe_cp.required' => 'Veuillez sélectionner un sexe.',
        'sexe_cp.in' => 'Le sexe sélectionné est invalide.',
        'num_siret.required_if' => 'Le numéro SIRET est requis pour un client professionnel.',
        'secteur_activite.required_if' => 'Veuillez sélectionner un secteur d\'activité pour un client professionnel.',
        'secteur_activite.exists' => 'Le secteur d\'activité sélectionné est invalide.',
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
    $client->prenom_cp = $validatedData['prenom_cp'];
    $client->nom_cp = $validatedData['nom_cp'];
    $client->mail_client = $validatedData['mail_client'];
    $client->mdp_client = Hash::make($validatedData['mdp_client']);
    $client->tel_client = $telClient;
    $client->date_naissance_cp = $request->input('date_naissance_cp', null);
    $client->sexe_cp = $validatedData['sexe_cp'];
    $client->est_particulier = $validatedData['est_particulier'];
    $client->num_siret = $validatedData['est_particulier'] == '0' ? $validatedData['num_siret'] : null;
    $client->id_sd = $validatedData['est_particulier'] == '0' ? $validatedData['secteur_activite'] : null;
    $client->photo = null;
    $client->est_verif = false;
    $client->code_verif = Str::random(6);
    $client->newsletter =
    $client->newsletter = $request->has('newsletter') ? true : false;
    
    $client->save();






    
    return redirect()->route('home')->with('success', 'Inscription réussie');
}
        
}