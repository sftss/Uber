<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Secteur;
use App\Mail\ConfirmationEmail; // Assurez-vous que le mail est bien importé
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Log;
use App\Models\User; // Pas besoin de require 'vendor/autoload.php' !
use SendGrid\Mail\Mail;

class RegisterController extends Controller
{
    // Afficher le formulaire d'inscription
    public function showRegistrationForm()
    {
        $secteurs = Secteur::all();
        return view('auth.register', compact('secteurs'));
    }

    public function showRegistrationFormch()
    {
        $secteurs = Secteur::all();
        return view('auth.registerch', compact('secteurs'));
    }

    // Enregistrer un client
    public function register(Request $request)
{
    $validatedData = $request->validate([
        'prenom_cp' => 'required|string|max:20',
        'nom_cp' => 'required|string|max:30',
        'mail_client' => 'required|email|max:70|unique:client,mail_client',
        'sexe_cp' => 'required|in:H,F,A',
        'mdp_client' => 'required|string|min:8|confirmed',
        'tel_client' => 'nullable|regex:/^0[67]\d{8}$/|max:10', // Validation du numéro de téléphone
        'est_particulier' => 'required|boolean',
        'num_siret' => 'required_if:est_particulier,0|nullable|string|max:14',
        'secteur_activite' => 'required_if:est_particulier,0|exists:secteur_d_activite,id_sd',
        'date_naissance_cp' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(), // Vérification de l'âge (au moins 18 ans)
    ], [
        'mdp_client.confirmed' => 'Les mots de passe ne correspondent pas.',
        'mdp_client.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'mail_client.unique' => 'Cette adresse email est déjà utilisée, veuillez en entrer une autre et réessayer.',
        'sexe_cp.required' => 'Veuillez sélectionner un sexe.',
        'sexe_cp.in' => 'Le sexe sélectionné est invalide.',
        'num_siret.required_if' => 'Le numéro SIRET est requis pour un client professionnel.',
        'secteur_activite.required_if' => 'Veuillez sélectionner un secteur d\'activité pour un client professionnel.',
        'secteur_activite.exists' => 'Le secteur d\'activité sélectionné est invalide.',
        'tel_client.regex' => 'Le numéro de téléphone doit commencer par 06 ou 07 et être composé de 10 chiffres.',
        'date_naissance_cp.before_or_equal' => 'Vous devez avoir au moins 18 ans.',
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
    $client->newsletter = $request->has('newsletter') ? true : false;
    

    
    $client->save();

    
        
    return redirect()->route('home')->with('success', 'Inscription réussie. Un email de confirmation vous a été envoyé.');
}




public function registerch(Request $request)
{
    $validatedData = $request->validate([
        'prenom_chauffeur' => 'required|string|max:20',
        'nom_chauffeur' => 'required|string|max:30',
        'mail_chauffeur' => 'required|email|max:70', // Vérifiez le nom de votre table et ajustez si nécessaire
        'sexe_chauffeur' => 'required|in:H,F,A',
        'mdp_chauffeur' => 'required|string|min:8|confirmed',
        'tel_chauffeur' => 'nullable|regex:/^0[67]\d{8}$/|max:10', // Numéro de téléphone français
        'num_siret' => 'required|string|max:14',
        'secteur_activite' => 'required|exists:secteur_d_activite,id_sd',
        'date_naissance_chauffeur' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString(), // Doit avoir au moins 18 ans
        'newsletter' => 'nullable|boolean',
    ], [
        'prenom_chauffeur.required' => 'Le prénom est requis.',
        'nom_chauffeur.required' => 'Le nom est requis.',
        'mail_chauffeur.required' => 'L\'adresse email est requise.',
        'mail_chauffeur.email' => 'L\'adresse email n\'est pas valide.',
        'sexe_chauffeur.required' => 'Veuillez sélectionner un sexe.',
        'sexe_chauffeur.in' => 'Le sexe sélectionné est invalide.',
        'mdp_chauffeur.required' => 'Le mot de passe est requis.',
        'mdp_chauffeur.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'mdp_chauffeur.confirmed' => 'Les mots de passe ne correspondent pas.',
        'tel_chauffeur.regex' => 'Le numéro de téléphone doit commencer par 06 ou 07 et être composé de 10 chiffres.',
        'tel_chauffeur.max' => 'Le numéro de téléphone ne peut pas dépasser 10 caractères.',
        'num_siret.max' => 'Le numéro SIRET ne peut pas dépasser 14 caractères.',
        'secteur_activite.exists' => 'Le secteur d\'activité sélectionné est invalide.',
        'date_naissance_chauffeur.required' => 'La date de naissance est requise.',
        'date_naissance_chauffeur.before_or_equal' => 'Vous devez avoir au moins 18 ans.',
    ]);
    
    // Nettoyage du numéro de téléphone
    $tel_chauffeur = $validatedData['tel_chauffeur'] ?? null;
    if ($tel_chauffeur) {
        $tel_chauffeur = preg_replace('/\D/', '', $tel_chauffeur);
        if (str_starts_with($tel_chauffeur, '0')) {
            $tel_chauffeur = substr($tel_chauffeur, 1);
        }
    }

    // Création du client dans la base de données
    $chauffeur = new Chauffeur();
    $chauffeur->prenom_chauffeur = $validatedData['prenom_chauffeur'];
    $chauffeur->nom_chauffeur = $validatedData['nom_chauffeur'];
    $chauffeur->mail_chauffeur = $validatedData['mail_chauffeur'];
    $chauffeur->mdp_chauffeur = Hash::make($validatedData['mdp_chauffeur']);
    $chauffeur->tel_chauffeur = $tel_chauffeur;
    $chauffeur->date_naissance_chauffeur = $request->input('date_naissance_chauffeur', null);
    $chauffeur->sexe_chauffeur = $validatedData['sexe_chauffeur'];
    $chauffeur->num_siret = $validatedData['num_siret'];
    $chauffeur->id_sd = $validatedData['secteur_activite'];
    $chauffeur->photo = null;
    $chauffeur->newsletter = $request->has('newsletter') ? true : false;
    
    $chauffeur->save();
        
    return redirect()->route('home')->with('success', 'Inscription réussie.');
}



        
}