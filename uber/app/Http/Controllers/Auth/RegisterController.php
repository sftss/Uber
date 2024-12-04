<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Afficher le formulaire d'inscription
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Enregistrer un client
    public function register(Request $request)
{
    // Validation des données
    $validator = Validator::make($request->all(), [
        'prenom_cp' => 'required|string|max:20',
        'nom_cp' => 'required|string|max:30',
        'mail_client' => 'required|email|max:70|unique:client,mail_client',
        'mdp_client' => 'required|string|min:8|confirmed', 
        'tel_client' => 'nullable|string|max:10',
        'num_siret' => 'nullable|string|max:14',
        'sexe_cp' => 'nullable|string|max:10',
        'date_naissance_cp' => 'nullable|date',
        'est_particulier' => 'nullable|boolean',
        'newsletter' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        // Si la validation échoue, afficher les erreurs
        return redirect()->back()->withErrors($validator)->withInput();
    }



    // Création d'un nouveau client
    $client = Client::create([
        'prenom_cp' => $request->prenom_cp,
        'nom_cp' => $request->nom_cp,
        'mail_client' => $request->mail_client,
        'mdp_client' => Hash::make($request->mdp_client),  // Hashage du mot de passe
        'id_sd' => 1,  // Vous pouvez ajuster cette valeur en fonction de votre logique
        'tel_client' => $request->tel_client,
        'num_siret' => $request->num_siret,
        'sexe_cp' => $request->sexe_cp,
        'date_naissance_cp' => $request->date_naissance_cp,
        'est_particulier' => $request->has('est_particulier'),  // true si la case est cochée, false sinon
        'newsletter' => $request->has('newsletter'),  // true si la case est cochée, false sinon
    ]);


    // Rediriger l'utilisateur après l'inscription
    return redirect()->route('home')->with('success', 'Inscription réussie !');
}
}