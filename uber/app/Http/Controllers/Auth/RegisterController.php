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
use App\Mail\ConfirmationEmail;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use SendGrid\Mail\Mail;
use Carbon\Carbon;
use App\Models\Adresse;


class RegisterController extends Controller
{
    public function showRegistrationSelection() {
        return view('auth.pageinscription');
    }

    public function showRegistrationForm() {
        $secteurs = Secteur::all();
        return view('auth.register', compact('secteurs'));
    }

    public function showRegistrationFormch() {
        $secteurs = Secteur::all();
        return view('auth.registerch', compact('secteurs'));
    }

    public function register(Request $request) {
        $validatedData = $request->validate([
            'prenom_cp' => 'required|string|max:20|regex:/^[a-zA-ZÀ-ÿ\-\'\s]+$/',
            'nom_cp' => 'required|string|max:30|regex:/^[a-zA-ZÀ-ÿ\-\'\s]+$/',
            'mail_client' => 'required|email|max:70|unique:client,mail_client',
            'sexe_cp' => 'required|in:H,F,A',
            'mdp_client' => 'required|string|min:8|confirmed',
            'tel_client' => 'nullable|regex:/^0[67]\d{8}$/|max:10', 
            'est_particulier' => 'required|boolean',
            'num_siret' => 'required_if:est_particulier,0|nullable|string|size:14|regex:/^\d{14}$/',
            'secteur_activite' => 'required_if:est_particulier,0|exists:secteur_d_activite,id_sd',
            'date_naissance_cp' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString() . '|after_or_equal:' . now()->subYears(100)->toDateString(),
        ], [
            'prenom_cp.regex' => 'Le prénom ne peut contenir que des lettres, espaces, apostrophes ou tirets.',
            'nom_cp.regex' => 'Le nom ne peut contenir que des lettres, espaces, apostrophes ou tirets.',
            'mdp_client.confirmed' => 'Les mots de passe ne correspondent pas.',
            'mdp_client.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mail_client.unique' => 'Cette adresse email est déjà utilisée, veuillez en entrer une autre et réessayer.',
            'sexe_cp.required' => 'Veuillez sélectionner un sexe.',
            'sexe_cp.in' => 'Le sexe sélectionné est invalide.',
            'num_siret.regex' => 'Le numéro SIRET doit être composé de 14 chiffres.',
            'num_siret.max' => 'Le numéro SIRET ne peut pas dépasser 14 caractères.',
            'num_siret.required_if' => 'Le numéro SIRET est requis pour un client professionnel.',
            'secteur_activite.required_if' => 'Veuillez sélectionner un secteur d\'activité pour un client professionnel.',
            'secteur_activite.exists' => 'Le secteur d\'activité sélectionné est invalide.',
            'tel_client.regex' => 'Le numéro de téléphone doit commencer par 06 ou 07 et être composé de 10 chiffres.',
            'date_naissance_cp.after_or_equal' => 'L\'âge ne peut pas dépasser 100 ans.',
            'date_naissance_cp.before_or_equal' => 'Vous devez avoir au moins 18 ans.',
        ]);
        
        // $dateInput = $request->input('date_naissance_cp');
        // if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $dateInput)) {
        //     $formattedDate = Carbon::createFromFormat('d/m/Y', $dateInput)->format('Y-m-d');
        //     $request->merge(['date_naissance_cp' => $formattedDate]);
        // }

        $telClient = $validatedData['tel_client'] ?? null;
        if ($telClient) {
            $telClient = preg_replace('/\D/', '', $telClient);
            if (str_starts_with($telClient, '0')) {
                $telClient = substr($telClient, 1);
            }
        }

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
        
        Log::info('Client créé', ['client' => $client]);

        return redirect()->route('home')->with('success', 'Inscription réussie. Un email de confirmation vous a été envoyé.');
    }

    public function registerch(Request $request) {
        $validatedData = $request->validate([
            'prenom_chauffeur' => 'required|string|max:20|regex:/^[a-zA-ZÀ-ÿ\-\'\s]+$/',
            'nom_chauffeur' => 'required|string|max:30|regex:/^[a-zA-ZÀ-ÿ\-\'\s]+$/',
            'mail_chauffeur' => 'required|email|max:70|unique:chauffeur,mail_chauffeur',
            'sexe_chauffeur' => 'required|in:H,F,A',
            'mdp_chauffeur' => 'required|string|min:8|confirmed',
            'tel_chauffeur' => 'nullable|regex:/^0[67]\d{8}$/|max:10', 
            'num_siret' => 'required|string|size:14|regex:/^\d{14}$/',
            'secteur_activite' => 'required|exists:secteur_d_activite,id_sd',
            'date_naissance_chauffeur' => 'required|date|before_or_equal:' . now()->subYears(18)->toDateString() . '|after_or_equal:' . now()->subYears(100)->toDateString(),
            'newsletter' => 'nullable|boolean',
            'type_service' => 'required|in:VTC,Livraison',
            'rue' => 'required|string|max:255', 
            'cp' => 'required|regex:/^\d{5}$/', 
            'ville' => 'required|string|max:100',
        ], [
            'prenom_chauffeur.regex' => 'Le prénom ne peut contenir que des lettres, espaces, apostrophes ou tirets.',
            'nom_chauffeur.regex' => 'Le nom ne peut contenir que des lettres, espaces, apostrophes ou tirets.',
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
            'num_siret.regex' => 'Le numéro SIRET doit être composé de 14 chiffres.',
            'num_siret.max' => 'Le numéro SIRET ne peut pas dépasser 14 caractères.',
            'secteur_activite.exists' => 'Le secteur d\'activité sélectionné est invalide.',
            'date_naissance_chauffeur.required' => 'La date de naissance est requise.',
            'date_naissance_chauffeur.before_or_equal' => 'Vous devez avoir au moins 18 ans.',
            'date_naissance_chauffeur.after_or_equal' => 'L\'âge ne peut pas dépasser 100 ans.',
            'rue.required' => 'La rue est obligatoire.',
            'rue.max' => 'La rue ne doit pas dépasser 255 caractères.',
            'cp.required' => 'Le code postal est obligatoire.',
            'cp.regex' => 'Le code postal doit être composé de 5 chiffres.',
            'ville.required' => 'La ville est obligatoire.',
            'ville.max' => 'Le nom de la ville ne doit pas dépasser 100 caractères.',
            'rib.required' => 'Le RIB est obligatoire.',
            
        ]);
        $numRib = 27;
        $rib = $request->input('rib');
        if ($rib) {
            $ribLength = strlen($rib);
            $letterCount = preg_match_all('/[A-Z]/', $rib);
            $digitCount = preg_match_all('/\d/', $rib);

            if ($ribLength != $numRib) {
                $missingChars = $numRib - $ribLength;
                return back()->withErrors(['rib' => "Le RIB doit contenir $numRib caractères. Il manque $missingChars caractère(s)."])->withInput();
            } elseif ($letterCount < 2) {
                $missingLetters = 2 - $letterCount;
                return back()->withErrors(['rib' => "Le RIB doit contenir 2 lettres. Il manque $missingLetters lettre(s)."])->withInput();
            } elseif ($digitCount < 25) {
                $missingDigits = 25 - $digitCount;
                return back()->withErrors(['rib' => "Le RIB doit contenir 25 chiffres. Il manque $missingDigits chiffre(s)."])->withInput();
            } elseif (!preg_match('/^[A-Z]{2}\d{25}$/', $rib)) {
                return back()->withErrors(['rib' => "Le format du RIB est incorrect."])->withInput();
            }
        }
        
        $tel_chauffeur = $validatedData['tel_chauffeur'] ?? null;
        if ($tel_chauffeur) {
            $tel_chauffeur = preg_replace('/\D/', '', $tel_chauffeur);
            if (str_starts_with($tel_chauffeur, '0')) {
                $tel_chauffeur = substr($tel_chauffeur, 1);
            }
        }

        $departement = substr($validatedData['cp'], 0, 2);
        if($departement > 19){
            $departement = $departement + 1;
        }

        $adresse = new Adresse();
        $adresse->id_departement = $departement;
        $adresse->rue = $validatedData['rue'];
        $adresse->ville = $validatedData['ville'];
        $adresse->cp = $validatedData['cp'];
        $adresse->save();

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
        $chauffeur->rib_chauffeur = $rib;
        $chauffeur->id_adresse_actuelle = $adresse->id_adresse;
        $chauffeur->newsletter = $request->has('newsletter') ? true : false; 
        $chauffeur->save();
            Log:info($chauffeur);
        return redirect()->route('home')->with('success', 'Inscription réussie.');
    }
}
