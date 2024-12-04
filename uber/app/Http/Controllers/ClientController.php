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

            // Récupérer les paniers associés à cet utilisateur
            $paniers = DB::table('client as c')
                ->leftJoin('commande_repas as cr', 'c.id_client', '=', 'cr.id_client')
                ->join('panier as p', 'cr.id_panier', '=', 'p.id_panier')
                ->leftJoin('est_contenu_dans as ecd', 'cr.id_commande_repas', '=', 'ecd.id_commande_repas')
                ->leftJoin('est_contenu_dans_menu as ecdm', 'cr.id_commande_repas', '=', 'ecdm.id_commande_repas')
                ->leftJoin('est_contenu_dans_plat as ecdp', 'cr.id_commande_repas', '=', 'ecdp.id_commande_repas')
                ->leftJoin('produit as pr', 'ecd.id_produit', '=', 'pr.id_produit')
                ->leftJoin('menu as m', 'ecdm.id_menu', '=', 'm.id_menu')
                ->leftJoin('plat as pl', 'ecdp.id_plat', '=', 'pl.id_plat')
                ->select(
                    'c.prenom_cp',
                    'c.nom_cp',
                    'cr.id_commande_repas',
                    'p.montant',
                    'pr.nom_produit',
                    'ecd.quantite as quantite_produit',
                    'm.libelle_menu',
                    'ecdm.quantite as quantite_menu',
                    'pl.libelle_plat',
                    'ecdp.quantite as quantite_plat'
                )
                ->where('c.id_client', $clientId)
                ->get();

            // Retourner la vue avec les paniers
            return view('panier', ['paniers' => $paniers]);
        } else {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }
    }
}
