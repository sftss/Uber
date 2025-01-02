<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Chauffeur;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showLoginFormCh()
    {
        return view('auth.loginch');
    }

    public function showLoginServiceForm()
    {
        return view('auth.loginservice');
    }

    public function showLoginSelection()
    {
        return view('auth.pagedeconnexion');
    }

    public function login(Request $request)
    {
        $request->validate([
            'mail_client' => 'required|email',
            'mdp_client' => 'required|string',
        ]);


        $client = Client::where('mail_client', $request->mail_client)->first();

        if ($client && Hash::check($request->mdp_client, $client->mdp_client)) {

            Auth::login($client);
            $role = $client->role->lib_role ?? 'guest';

            if($client->est_verif == null){
                return redirect()->route('verifiermail')->with('success', 'Inscription réussie. Un email de confirmation vous a été envoyé.');
            }
            else{
                session(['role' => $role]);
                return redirect('/');
            }
        }
        return redirect()->back()->withErrors(['mail_client' => 'Les informations de connexion sont incorrectes.']);
    }

    public function logout()
    {
        Auth::guard('clients')->logout(); // Utilisez 'clients' ici
        return redirect()->route('home')->with('success', 'Déconnexion réussie.');
    }

    public function loginch(Request $request)
    {
        $request->validate([
            'mail_chauffeur' => 'required|email',
            'mdp_chauffeur' => 'required|string',
        ]);


        $Chauffeur = Chauffeur::where('mail_chauffeur', $request->mail_chauffeur)->first();
        if (!$Chauffeur) {
            // Si aucun chauffeur n'est trouvé, renvoyez une réponse ou une erreur
            return redirect()->back()->withErrors(['email' => 'Chauffeur non trouvé.']);
        }
        Auth::guard('chauffeurs')->login($Chauffeur);

        if ($Chauffeur && Hash::check($request->mdp_chauffeur, $Chauffeur->mdp_chauffeur)) {
            //session(['role' => $role]);

            return redirect()->route('home')->with('success', 'Inscription réussie. Un email de confirmation vous a été envoyé.');
        }
        return redirect()->back()->withErrors(['mail_chauffeur' => 'Les informations de connexion sont incorrectes.']);
    }

    public function logoutCh()
    {
        Auth::guard('chauffeurs')->logout(); // Utilisez 'chauffeurs' ici
        return redirect()->route('home')->with('success', 'Déconnexion réussie.');
    }
}