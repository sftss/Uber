<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function filter(Request $request) 
    {
        // Récupérer les paramètres de la requête
        $recherche = $request->input('lieu');
        $livre = $request->has('livre');
        $emporter = $request->has('emporter');
        $categorie = $request->input('categorie');
        $horraire = $request->input('horaire-selected');
        
        $horaireOuverture = null;
        $horaireFermeture = null;

        if ($horraire) {
            [$horaireOuverture, $horaireFermeture] = array_map(
                fn($time) => date('H:i:s', strtotime(trim($time))),
                explode(' - ', $horraire)
            );
        }

        $restaurants = DB::table('restaurant')
            ->join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
            ->leftJoin('a_pour_categorie', 'restaurant.id_restaurant', '=', 'a_pour_categorie.id_restaurant')
            ->leftJoin('categorie_restaurant', 'a_pour_categorie.id_categorie', '=', 'categorie_restaurant.id_categorie')
            ->when($recherche, function ($query, $recherche) {
                return $query->where(function ($q) use ($recherche) {
                    $q->whereRaw('LOWER(adresse.ville) LIKE LOWER(?)', ['%' . $recherche . '%'])
                    ->orWhereRaw('LOWER(restaurant.nom_etablissement) LIKE LOWER(?)', ['%' . $recherche . '%']);
                });
            })
            ->when($livre || $emporter, function ($query) use ($livre, $emporter) {
                $query->where(function ($q) use ($livre, $emporter) {
                    if ($livre) {
                        $q->where('restaurant.propose_livraison', 1);
                    }
                    if ($emporter) {
                        $q->where('restaurant.propose_retrait', 1);
                    }
                });
            })
            ->when($categorie, function ($query, $categorie) {
                return $query->where('categorie_restaurant.id_categorie', $categorie);
            })
            ->when($horaireOuverture && $horaireFermeture, function ($query) use ($horaireOuverture, $horaireFermeture) {
                return $query->where(function ($q) use ($horaireOuverture, $horaireFermeture) {
                    // Cas 1 : Restaurant ferme après l'heure d'ouverture et dans la même journée
                    $q->where(function ($subQuery) use ($horaireOuverture, $horaireFermeture) {
                        $subQuery->whereRaw('? BETWEEN restaurant.horaires_ouverture AND restaurant.horaires_fermeture', [$horaireOuverture])
                                ->whereRaw('restaurant.horaires_fermeture > ?', [$horaireOuverture]) // Exclut les fermetures à l'heure exacte
                                ->orWhereRaw('? BETWEEN restaurant.horaires_ouverture AND restaurant.horaires_fermeture', [$horaireFermeture]);
                    })
                    ->orWhere(function ($subQuery) use ($horaireOuverture, $horaireFermeture) {
                        $subQuery->whereRaw('restaurant.horaires_fermeture < restaurant.horaires_ouverture') // Chevauchement minuit
                                ->where(function ($nestedQuery) use ($horaireOuverture, $horaireFermeture) {
                                    $nestedQuery
                                        ->whereRaw('? >= restaurant.horaires_ouverture', [$horaireOuverture]) // Plage après ouverture
                                        ->orWhereRaw('? <= restaurant.horaires_fermeture', [$horaireFermeture]); // Plage avant fermeture
                                });
                    });
                });
            })
            ->select('restaurant.*', 'adresse.ville', 'categorie_restaurant.lib_categorie', 'restaurant.horaires_ouverture', 'restaurant.horaires_fermeture')
            ->distinct()
            ->get();

        $categories = DB::table('categorie_restaurant')->get();

        return view('restaurants.filter', compact('restaurants', 'recherche', 'categories', 'horaireOuverture', 'horaireFermeture'));
    }


    
    public function show($id, Request $request)
{
    $restaurant = DB::table('restaurant')
        ->join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
        ->leftJoin('a_pour_categorie', 'restaurant.id_restaurant', '=', 'a_pour_categorie.id_restaurant')
        ->leftJoin('categorie_restaurant', 'a_pour_categorie.id_categorie', '=', 'categorie_restaurant.id_categorie')
        ->select('restaurant.*', 'adresse.ville', 'adresse.rue', 'adresse.cp', 'categorie_restaurant.lib_categorie')
        ->where('restaurant.id_restaurant', $id)
        ->first();

    if (!$restaurant) {
        abort(404, 'Restaurant non trouvé');
    }

    $recherche = $request->input('recherche');
    $categorie = $request->input('categorie');

    $menus = DB::table('menu')
        ->join('compose_de', 'menu.id_menu', '=', 'compose_de.id_menu')
        ->join('plat', 'compose_de.id_plat', '=', 'plat.id_plat')
        ->leftJoin('categorie_produit', 'plat.id_categorie_produit', '=', 'categorie_produit.id_categorie_produit')
        ->where('menu.id_restaurant', $id)
        ->when($recherche, function ($query, $recherche) {
            return $query->where(function ($q) use ($recherche) {
                $q->whereRaw('LOWER(menu.libelle_menu) LIKE LOWER(?)', ['%' . $recherche . '%'])
                  ->orWhereRaw('LOWER(plat.libelle_plat) LIKE LOWER(?)', ['%' . $recherche . '%']);
            });
        })
        ->when($categorie, function ($query, $categorie) {
            return $query->where('plat.id_categorie_produit', $categorie);
        })
        ->select('menu.*', 'plat.libelle_plat', 'categorie_produit.libelle_categorie as categorie_produit')
        ->get();

    $plats = DB::table('plat')
        ->leftJoin('propose', 'plat.id_plat', '=', 'propose.id_plat')
        ->leftJoin('restaurant', 'propose.id_restaurant', '=', 'restaurant.id_restaurant')
        ->leftJoin('categorie_produit', 'plat.id_categorie_produit', '=', 'categorie_produit.id_categorie_produit')
        ->where('restaurant.id_restaurant', $id)
        ->when($recherche, function ($query, $recherche) {
            return $query->whereRaw('LOWER(plat.libelle_plat) LIKE LOWER(?)', ['%' . $recherche . '%']);
        })
        ->when($categorie, function ($query, $categorie) {
            return $query->where('plat.id_categorie_produit', $categorie);
        })
        ->select('plat.*', 'categorie_produit.libelle_categorie as categorie_plat')
        ->get();

        $produits = DB::table('vends')
        ->join('produit', 'vends.id_produit', '=', 'produit.id_produit')
        ->leftJoin('categorie_produit', 'produit.id_categorie_produit', '=', 'categorie_produit.id_categorie_produit') // Ajout pour les catégories
        ->select('produit.*', 'categorie_produit.libelle_categorie as categorie_produit')
        ->where('vends.id_restaurant', $id)
        ->when($recherche, function ($query, $recherche) {
            return $query->where(function ($q) use ($recherche) {
                $q->whereRaw('LOWER(produit.nom_produit) LIKE LOWER(?)', ['%' . $recherche . '%']);
            });
        })
        ->when($categorie, function ($query, $categorie) {
            return $query->where('produit.id_categorie_produit', $categorie);
        })
        ->get();

    $categories = DB::table('categorie_produit')->get(); // Pour autre filtre
    $categorieId = $request->input('categorie', ''); // Catégorie sélectionnée
    $categoriesProduits = DB::table('categorie_produit')->get();

    return view('restaurants.show', compact('restaurant', 'menus', 'plats', 'produits', 'categoriesProduits', 'categorieId'));
}





}
