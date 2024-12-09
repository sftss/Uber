<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Ajouter un middleware pour s'assurer que l'utilisateur est authentifié
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function panierclient(Request $request)
    {
        // Vérifier si l'utilisateur est authentifié
        if (Auth::check()) {
            // Récupérer l'ID du client connecté
            $clientId = Auth::user()->id_client;

            $paniers = DB::table('panier as p')
            ->leftJoin('contient as c', 'p.id_panier', '=', 'c.id_panier')
            ->leftJoin('contient_plat as cp', 'p.id_panier', '=', 'cp.id_panier')
            ->leftJoin('contient_menu as cm', 'p.id_panier', '=', 'cm.id_panier')
            ->leftJoin('produit as pr', 'c.id_produit', '=', 'pr.id_produit')
            ->leftJoin('plat as pl', 'cp.id_plat', '=', 'pl.id_plat')
            ->leftJoin('menu as m', 'cm.id_menu', '=', 'm.id_menu')
            ->leftJoin('commande_repas as cr', 'p.id_panier', '=', 'cr.id_panier')
            ->leftJoin('client as cli', 'cr.id_client', '=', 'cli.id_client')
            ->select(
                'cr.id_commande_repas', 'p.montant', 'pr.nom_produit', 'c.quantite as quantite_produit', 
                'm.libelle_menu', 'cm.quantite as quantite_menu', 'pl.libelle_plat', 'cp.quantite as quantite_plat',
                'cli.prenom_cp', 'cli.nom_cp'
            )
            ->where('cr.id_client', '=', $clientId)
            ->get();

        

            // Retourner la vue avec les paniers
            return view('panier', ['paniers' => $paniers]);
        } else {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
    }
    public function profil($id)
{
    // Récupération de l'utilisateur connecté
    $currentUser = auth()->user();

    // Vérification que l'utilisateur connecté est autorisé à accéder à cette page
    if (!$currentUser || $currentUser->id_client != $id) {
        return redirect('/')->with('error', 'Vous n\'êtes pas autorisé à accéder à cette page.');
    }

    // Récupération des données du client et des cartes bancaires associées
    $client = DB::table('client as c')
        ->leftJoin('possede as p', 'p.id_client', '=', 'c.id_client')
        ->leftJoin('cb as cb', 'cb.id_cb', '=', 'p.id_cb')
        ->select('c.prenom_cp', 'c.nom_cp', 'c.mail_client', 'c.tel_client', 'cb.num_cb', 'cb.nom_cb', 'cb.date_fin_validite','cb.type_cb')
        ->where('c.id_client', $id)
        ->get();

    // Vérification que le client existe
    if ($client->isEmpty()) {
        return redirect('/')->with('error', 'Utilisateur introuvable.');
    }

    // Retourner la vue avec les informations du client et des cartes
    return view('auth.profil', ['client' => $client]);
}



}
