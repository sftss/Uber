<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LieuVenteController extends Controller
{
    public function filter(Request $request)
{
    // Récupérer les paramètres de la requête
    $recherche = $request->input('lieu');
    $livre = $request->boolean('livre'); // Vérifie directement si la checkbox est cochée
    $horraire = $request->input('horaire-selected');

        
    $horaireOuverture = null;
    $horaireFermeture = null;

    if ($horraire) {
        [$horaireOuverture, $horaireFermeture] = array_map(
            fn($time) => date('H:i:s', strtotime(trim($time))),
            explode(' - ', $horraire)
        );
    }


    // Construire la requête avec les filtres
    $lieux = DB::table('lieu_de_vente_pf')
    
    ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
    ->when($recherche, function ($query, $recherche) {

        return $query->where(function ($q) use ($recherche) {
            $q->whereRaw('LOWER(adresse.ville) LIKE LOWER(?)', ['%' . $recherche . '%'])
            ->orWhereRaw('LOWER(lieu_de_vente_pf.nom_etablissement) LIKE LOWER(?)', ['%' . $recherche . '%']);
        });
    })
    ->when($livre , function ($query) use ($livre) {
        $query->where(function ($q) use ($livre) {
            if ($livre) {
                $q->where('lieu_de_vente_pf.propose_livraison', 'true');
            }
        });
    })
    ->when($horaireOuverture && $horaireFermeture, function ($query) use ($horaireOuverture, $horaireFermeture) {
        return $query->where(function ($q) use ($horaireOuverture, $horaireFermeture) {
            $q->whereRaw('lieu_de_vente_pf.horaires_ouverture <= ?', [$horaireOuverture])
              ->whereRaw('lieu_de_vente_pf.horaires_fermeture >= ?', [$horaireFermeture]);
        });
    })
      
    ->select('lieu_de_vente_pf.*', 'adresse.ville','lieu_de_vente_pf.horaires_ouverture', 'lieu_de_vente_pf.horaires_fermeture')
    ->distinct()
    ->get();

    return view('lieux.filter', compact('lieux'));
}



    public function show($id_lieu_de_vente_pf, Request $request)
{
    // Récupérer le lieu de vente
    $lieu = DB::table('lieu_de_vente_pf')
        ->join('adresse', 'lieu_de_vente_pf.id_adresse', '=', 'adresse.id_adresse')
        ->select('id_lieu_de_vente_pf', 'nom_etablissement', 'horaires_ouverture', 'horaires_fermeture', 'description_etablissement', 'propose_livraison', 'photo_lieu', 'adresse.ville', 'adresse.rue', 'adresse.cp')
        ->where('id_lieu_de_vente_pf', $id_lieu_de_vente_pf)
        ->first();

    if (!$lieu) {
        abort(404, 'Lieu de vente non trouvé');
    }

    // Récupérer la liste des catégories
    $categories = DB::table('categorie_produit')->get();

    $produitRecherche = $request->input('produit'); // Nom du produit recherché
    $categorieRecherche = $request->input('categorie'); // Catégorie sélectionnée

    // Construire la requête pour récupérer les produits
    $produits = DB::table('est_vendu')
        ->join('produit', 'est_vendu.id_produit', '=', 'produit.id_produit')
        ->join('categorie_produit', 'produit.id_categorie_produit', '=', 'categorie_produit.id_categorie_produit')
        ->select('produit.id_produit', 'produit.nom_produit', 'produit.prix_produit', 'produit.photo_produit', 'categorie_produit.libelle_categorie')
        ->where('est_vendu.id_lieu_de_vente_pf', $id_lieu_de_vente_pf);

    if ($produitRecherche) {
        $produits->whereRaw('LOWER(produit.nom_produit) LIKE LOWER(?)', ['%' . $produitRecherche . '%']);
    }

    if ($categorieRecherche) {
        $produits->where('produit.id_categorie_produit', $categorieRecherche);
    }

    $produits = $produits->get();

    return view('lieux.show', compact('lieu', 'produits', 'categories'));
}


}
