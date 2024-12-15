<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignUp;
use Illuminate\Support\Facades\Auth;


class MailController extends Controller
{
    public function sendMail(){

        if(Auth::check()){

        
        $mail = Auth::user()->mail_client;
        $codeverif=Auth::user()->code_verif;
        Mail::to($mail)->send(new SignUp($codeverif));

        return view('auth.verification');
        }
    }
    public function verifyCode(Request $request)
{

    $request->validate([
        'code' => 'required|string|max:255',
    ]);


    $userInputCode = $request->input('code');

    // Exemple : Supposons que le code correct est stocké dans la base de données pour l'utilisateur actuel
    $correctCode = auth()->user()->code_verif; // À adapter selon votre structure

    if ($userInputCode === $correctCode) {
        $user = auth()->user();
        $user->est_verif = true; 
        $user->save();

        return redirect()->route('home')->with('success', 'Inscription réussie. Un email de confirmation vous a été envoyé.');
    } else {
        return back()->withErrors(['code' => 'Le code de vérification est incorrect.']);
    }
}

}
