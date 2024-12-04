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


        $client = Client::where('mail_client', $request->mail_client)->first();

        if ($client && Hash::check($request->mdp_client, $client->mdp_client)) {

            Auth::login($client);

            return redirect('/');
        }
        return redirect()->back()->withErrors(['mail_client' => 'Les informations de connexion sont incorrectes.']);
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}