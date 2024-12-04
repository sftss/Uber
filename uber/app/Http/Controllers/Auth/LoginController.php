<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class LoginController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Gérer la soumission du formulaire de connexion
    public function login(Request $request)
    {
        // Validation des données
        $request->validate([
            'mail_client' => 'required|email',
            'mdp_client' => 'required|string',
        ]);

        // Vérification des informations de connexion
        $client = Client::where('mail_client', $request->mail_client)->first();

        if ($client && Hash::check($request->mdp_client, $client->mdp_client)) {
            // Connexion réussie, authentifier l'utilisateur
            Auth::login($client);

            // Rediriger vers la page d'accueil ou tableau de bord
            return redirect('/'); // Redirige vers la page d'accueil après la connexion
        }

        // Si les informations d'identification sont incorrectes
        return redirect()->back()->withErrors(['mail_client' => 'Les informations de connexion sont incorrectes.']);
    }

    // Déconnexion
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}