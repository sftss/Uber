<?php

namespace App\Http\Controllers;

use App\Mail\NotifChauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignUp;
use App\Mail\Paiement;
use App\Models\Chauffeur;
use Illuminate\Support\Facades\Auth;


class MailController extends Controller
{
    public function sendMail(){

        
        if(Auth::check()){

        
        $mail = Auth::user()->mail_client;
        $codeverif=Auth::user()->code_verif;
        Mail::to("$mail")->send(new SignUp($codeverif));

        return view('auth.verification');
        }
    }

    public function sendMailPaiement(){

        $mail = Auth::user()->mail_client;
        Mail::to("$mail")->send(new Paiement());
        return view("cart.confirmed")->with(
            "success",
            "Votre commande a été validée avec succès."
        );

    }
    

    public function verifyCode(Request $request)
{

    $request->validate([
        'code' => 'required|string|max:255',
    ]);
    
    $userInputCode = $request->input('code');
    
    


    $correctCode = auth()->user()->code_verif;


    if ($userInputCode === $correctCode) {
        $user = auth()->user();
        $user->est_verif = true; 
        $user->save();

        return redirect()->route('home')->with('success', 'Inscription réussie. Un email de confirmation vous a été envoyé.');
    } else {
        return back()->withErrors(['code' => 'Le code de vérification est incorrect.']);
    }
}

public function SendNotifChauffeur(Request $request)
{
    // Validation de la requête pour s'assurer qu'on reçoit bien une liste d'ID chauffeur
    $validated = $request->validate([
        'chauffeurs' => 'required|array',  // On s'attend à une liste d'ID
        'chauffeurs.*' => 'integer|exists:chauffeur,id_chauffeur',  // Chaque ID doit être un entier existant dans la table 'chauffeur' et la colonne 'id_chauffeur'
    ]);

    // Récupérer les chauffeurs correspondants aux IDs donnés
    $chauffeurs = Chauffeur::whereIn('id_chauffeur', $validated['chauffeurs'])->get();

    // Vérifier si des chauffeurs ont été trouvés
    if ($chauffeurs->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Aucun chauffeur trouvé pour les IDs fournis.',
        ], 404);
    }

    // Envoi des notifications par email à chaque chauffeur
    foreach ($chauffeurs as $chauffeur) {
        // Vous devez probablement ajuster la récupération de l'email (ex: $chauffeur->email)
        Mail::to($chauffeur->mail_chauffeur)->send(new NotifChauffeur());
    }

    // Envoi à un autre destinataire (si nécessaire)
    Mail::to("tanguyabdoulvaid@gmail.com")->send(new NotifChauffeur());

    // Retourner une réponse de succès
    return response()->json([
        'status' => 'success',
        'message' => 'Courses créées avec succès pour tous les chauffeurs.',
    ]);
}


}
