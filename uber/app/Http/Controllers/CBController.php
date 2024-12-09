<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CB;
use App\Models\Possede;

class CBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // S'assure que l'utilisateur est authentifié avant d'accéder aux actions
    }
    public function store(Request $request, $id_client)
{
    // Validation des champs
    $validatedData = $request->validate([
        'num_cb' => 'required|numeric|digits:16',  // Validation du numéro de carte
        'nom_cb' => 'required|string|max:50', // Validation du nom du titulaire
        'date_fin_validite' => 'required|date_format:Y-m', // Validation de la date
    ]);

    // Ajouter le jour par défaut à la date (le premier jour du mois)
    $dateFinValidite = $validatedData['date_fin_validite'] . '-01'; // Exemple : "2026-12-01"

    // Déterminer le type de carte en fonction du numéro
    $cardType = $this->determineCardType($validatedData['num_cb']);
    try {
        // Créer une nouvelle carte bancaire avec le type déterminé
        $card = new CB();
        $card->num_cb = $validatedData['num_cb'];
        $card->nom_cb = $validatedData['nom_cb'];
        $card->date_fin_validite = $dateFinValidite; // Utiliser la date corrigée
        $card->type_cb = $cardType;
        $card->save();


        $possede=new Possede();
        $possede->id_client=$id_client;
        $possede->id_cb=$card->id_cb;
        $possede->save();


        // Redirection avec succès
        return redirect()->route('profil', ['id_client' => $id_client])
            ->with('success', 'Carte bancaire ajoutée avec succès.');
    } catch (\Exception $e) {
        // Log des erreurs
        \Log::error('Erreur lors de l\'ajout de la carte : ' . $e->getMessage());
        return redirect()->back()->with('error', $e->getMessage());
    }
}



// Fonction pour déterminer le type de la carte bancaire
protected function determineCardType($cardNumber)
{
    // Retirer les espaces et les tirets, si présents
    $cardNumber = preg_replace('/\D/', '', $cardNumber);

    // Vérifier les préfixes des numéros de cartes pour identifier le type

    // Visa commence par 4
    if (preg_match('/^4/', $cardNumber)) {
        return 'Visa';
    }

    // MasterCard commence par 51-55, 2221-2720
    if (preg_match('/^5[1-5]/', $cardNumber) || preg_match('/^22[2-9]/', $cardNumber)) {
        return 'MasterCard';
    }

    // AMEX commence par 34 ou 37
    if (preg_match('/^3[47]/', $cardNumber)) {
        return 'AMEX';
    }

    // Diners Club commence par 36 ou 38
    if (preg_match('/^36|38/', $cardNumber)) {
        return 'Diners';
    }

    // JCB commence par 3528-3589
    if (preg_match('/^35[28-9]/', $cardNumber)) {
        return 'JCB';
    }

    // Si aucun type ne correspond, retourner 'Unknown'
    return 'Inconnu';
}




    public function create()
    {
        return view('auth.add-card');
    }
}
