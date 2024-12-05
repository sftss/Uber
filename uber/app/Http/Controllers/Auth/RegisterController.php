<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\ConfirmationEmail; // Assurez-vous que le mail est bien importé


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
                // Message personnalisé pour le champ mdp_client
                'mdp_client.confirmed' => 'Les mots de passe ne correspondent pas. Veuillez vérifier et réessayer.',
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
            $client->prenom_cp = $validatedData['prenom_cp'];
            $client->nom_cp = $validatedData['nom_cp'];
            $client->mail_client = $validatedData['mail_client'];
            $client->mdp_client = Hash::make($validatedData['mdp_client']);
            $client->tel_client = $telClient;
            $client->date_naissance_cp = $request->input('date_naissance_cp', null);
            $client->sexe_cp = $request->input('sexe_cp', null);
        
            // Gestion des checkboxes
            $client->est_particulier = $request->has('est_particulier') ? true : false;
            $client->newsletter = $request->has('newsletter') ? true : false;
        
            // Valeurs par défaut pour les champs restants
            $client->id_sd = 1;
            $client->num_siret = null;
            $client->photo = null;
        
        
            $client->code_verif = Str::random(6); // Code de vérification aléatoire
        
            $client->save(); // Enregistrer le client dans la base de données
        

            // Rediriger vers la page d'accueil ou une autre page après l'inscription
            return redirect()->route('home')->with('success', 'Inscription réussie');
        }
        
}