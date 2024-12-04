<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LieuVenteController extends Controller
{
    public function filter()
    {
        $lieux = DB::table('lieu_de_vente_pf')
            ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
            ->select('id_lieu_de_vente_pf', 'nom_etablissement', 'horaires_ouverture', 'horaires_fermeture', 'description_etablissement', 'propose_livraison', 'photo_lieu', 'adresse.ville', 'adresse.rue', 'adresse.cp')
            ->get();

        return view('lieux.filter', compact('lieux'));
    }

    public function show($id_lieu_de_vente_pf)
    {
        // Récupérer le lieu de vente avec l'ID
        $lieu = DB::table('lieu_de_vente_pf')
            ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
            ->select('id_lieu_de_vente_pf', 'nom_etablissement', 'horaires_ouverture', 'horaires_fermeture', 'description_etablissement', 'propose_livraison', 'photo_lieu', 'adresse.ville', 'adresse.rue', 'adresse.cp')
            ->where('id_lieu_de_vente_pf', $id_lieu_de_vente_pf)
            ->first();

        if (!$lieu) {
            abort(404, 'Lieu de vente non trouvé');
        }

        // Récupérer les produits associés à ce lieu
        $produits = DB::table('est_vendu')
            ->join('produit', 'est_vendu.id_produit', '=', 'produit.id_produit')
            ->select('produit.nom_produit', 'produit.prix_produit','produit.photo_produit')
            ->where('est_vendu.id_lieu_de_vente_pf', $id_lieu_de_vente_pf)
            ->get();

        // Retourner la vue avec le lieu et les produits
        return view('lieux.show', compact('lieu', 'produits'));
    }

}
