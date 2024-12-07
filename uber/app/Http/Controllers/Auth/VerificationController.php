<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class VerificationController extends Controller
{
    // Afficher la page de vérification
    public function showVerificationPage()
    {
        return view('auth.verification');
    }

    // Vérifier le code
    public function verifyCode(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|max:6',
        ]);

        // Rechercher le client
        $client = Client::where('mail_client', $validatedData['email'])->first();

        if (!$client || $client->code_verif !== $validatedData['code']) {
            return back()->withErrors(['code' => 'Le code est incorrect ou l\'email est invalide.']);
        }

        // Marquer le client comme vérifié
        $client->est_verif = true;
        $client->code_verif = null; // Supprime le code après validation
        $client->save();

        return redirect()->route('home')->with('success', 'Votre compte a été vérifié avec succès !');
    }
}
