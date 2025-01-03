<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Adresse;
use App\Models\Restaurant;
use App\Models\Chauffeur;
use App\Models\Jour;
use App\Models\CommandeRepas;
use App\Models\HorairesRestaurant;
use App\Models\APourCategorie;
use Illuminate\Support\Facades\Auth;


class RestaurantController extends Controller
{
    public function filter(Request $request) {
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
            ->leftJoin('horaires_restaurant', function ($join) {
                $join->on('restaurant.id_restaurant', '=', 'horaires_restaurant.id_restaurant')
                    ->where('horaires_restaurant.id_jour', '=', DB::raw('EXTRACT(DOW FROM CURRENT_DATE)')); // Récupérer les horaires du jour actuel
            })
        ->select('restaurant.*', 'adresse.*', 'categorie_restaurant.*', 'horaires_restaurant.horaires_ouverture', 'horaires_restaurant.horaires_fermeture')

            ->when($recherche, function ($query, $recherche) {
                return $query->where(function ($q) use ($recherche) {
                    $q->whereRaw('LOWER(adresse.ville) LIKE LOWER(?)', ['%' . $recherche . '%'])
                    ->orWhereRaw('LOWER(restaurant.nom_etablissement) LIKE LOWER(?)', ['%' . $recherche . '%']);
                });
            })
            ->when($livre || $emporter, function ($query) use ($livre, $emporter) {
                $query->where(function ($q) use ($livre, $emporter) {
                    if ($livre) $q->where('restaurant.propose_livraison', 1);
                    if ($emporter) $q->where('restaurant.propose_retrait', 1);
                });
            })
            ->distinct()
            ->paginate(10);
            $categories = DB::table('categorie_restaurant')->get();


            return view('restaurants.filter', compact('restaurants', 'recherche', 'categories', 'horaireOuverture', 'horaireFermeture'));
    }

    public function show($id, Request $request) {
        $restaurant = DB::table('restaurant')
            ->join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
            ->leftJoin('a_pour_categorie', 'restaurant.id_restaurant', '=', 'a_pour_categorie.id_restaurant')
            ->leftJoin('categorie_restaurant', 'a_pour_categorie.id_categorie', '=', 'categorie_restaurant.id_categorie')
            ->leftJoin('horaires_restaurant', function ($join) {
                $join->on('restaurant.id_restaurant', '=', 'horaires_restaurant.id_restaurant')
                    ->where('horaires_restaurant.id_jour', '=', DB::raw('EXTRACT(DOW FROM CURRENT_DATE)')); // Récupérer les horaires du jour actuel
            })
            ->select(
                'restaurant.*',
                'adresse.ville',
                'adresse.rue',
                'adresse.cp',
                'categorie_restaurant.lib_categorie',
                'horaires_restaurant.horaires_ouverture',
                'horaires_restaurant.horaires_fermeture'
            )
            ->where('restaurant.id_restaurant', $id)
            ->first();

        $recherche = $request->input('recherche');
        $categorie = $request->input('categorie');

        $horaires = DB::table('horaires_restaurant as h')
            ->join('jour as j', 'j.id_jour', '=', 'h.id_jour')
            ->where('h.id_restaurant', '=', $id)
            ->select('j.lib_jour as jour', 'h.horaires_ouverture', 'h.horaires_fermeture')
            ->orderBy('h.id_jour')
            ->get();
        $menus = DB::table('menu')
            ->leftjoin('compose_de', 'menu.id_menu', '=', 'compose_de.id_menu')
            ->leftjoin('plat', 'compose_de.id_plat', '=', 'plat.id_plat')
            ->leftJoin('categorie_produit', 'plat.id_categorie_produit', '=', 'categorie_produit.id_categorie_produit')
            ->leftJoin('propose_menu', 'menu.id_menu', '=', 'propose_menu.id_menu')
            ->leftJoin('restaurant', 'propose_menu.id_restaurant', '=', 'restaurant.id_restaurant')  
          
            ->where('propose_menu.id_restaurant', $id)
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
        // $menus = DB::table('menu');

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
        $categories = DB::table('categorie_produit')->get(); 
        $categorieId = $request->input('categorie', '');
        $categoriesProduits = DB::table('categorie_produit')->get();

        return view('restaurants.show', compact('restaurant', 'menus', 'plats', 'produits', 'categoriesProduits', 'categorieId','horaires'));
    }

    public function create() {
        $adresses = DB::table('adresse')->get();
        $categories = DB::table('categorie_restaurant')->get(); 
        $jours = Jour::all();
        return view('restaurants.create', compact('adresses', 'jours', 'categories'));
    }

    public function store(Request $request) {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validate([
                'rue' => 'required|string|max:255',
                'cp' => 'required|digits:5',
                'ville' => 'required|string|max:100',
                'nom_etablissement' => 'required|string|max:255',
                'description_etablissement' => 'nullable|string',
                'propose_livraison' => 'boolean',
                'propose_retrait' => 'boolean',
                'category' => 'required|exists:categorie_restaurant,id_categorie',
                'horaires' => 'required|array',
                'horaires.*.ouverture' => 'nullable|date_format:H:i',
                'horaires.*.fermeture' => 'nullable|date_format:H:i',
                'horaires.*.ferme' => 'nullable|boolean',
                'photo_restaurant' => 'required|string|max:500',
            ]);
            Log::info('Données validées', $validatedData);

            foreach ($validatedData['horaires'] as $jourId => $horaire) {
                if (isset($horaire['ouverture'], $horaire['fermeture']) && $horaire['ouverture'] >= $horaire['fermeture']) {
                    throw ValidationException::withMessages([
                        "horaires.{$jourId}.ouverture" => "L'heure d'ouverture doit précéder l'heure de fermeture pour le jour {$jourId}.",
                    ]);
                }
            }

            $departement = substr($validatedData['cp'], 0, 2);
            if($departement > 19){
                $departement = $departement + 1;
            }
            Log::info('Département créé', ['departement' => $departement]);

            $adresse = Adresse::create([
                'id_departement' => $departement,
                'rue' => $validatedData['rue'],
                'ville' => $validatedData['ville'],
                'cp' => $validatedData['cp'],
            ]);
            Log::info('Adresse créée', ['adresse' => $adresse]);

            $restaurant = Restaurant::create([
                'nom_etablissement' => $validatedData['nom_etablissement'],
                'description_etablissement' => $validatedData['description_etablissement'],
                'propose_livraison' => $request->has('propose_livraison') ? 1 : 0,
                'propose_retrait' => $request->has('propose_retrait') ? 1 : 0,
                'horaires_ouverture' => $horaire['ouverture'] ?? null,
                'horaires_fermeture' => $horaire['fermeture'] ?? null,
                'ferme' => isset($horaire['ferme']) ? 1 : 0,
                'id_adresse' => $adresse->id_adresse,
                'photo_restaurant' => $validatedData['photo_restaurant'],
                'id_proprietaire' => Auth::user()->id_client,
            ]);
            Log::info('Restaurant créé', ['restaurant' => $restaurant]);

            foreach ($validatedData['horaires'] as $jourId => $horaire) {
                HorairesRestaurant::create([
                    'id_jour' => $jourId,
                    'id_restaurant' => $restaurant->id_restaurant,
                    'horaires_ouverture' => $horaire['ouverture'] ?? null,
                    'horaires_fermeture' => $horaire['fermeture'] ?? null,
                    'ferme' => isset($horaire['ferme']) ? 1 : 0,
                ]);
            }
            Log::info('Horaire restaurant créé', ['HorairesRestaurant' => $horaire]);
            
            DB::table('a_pour_categorie')->insert([
                'id_restaurant' => $restaurant->id_restaurant,
                'id_categorie' => $validatedData['category'],
            ]);
        });
        Log::info('Transaction terminée');

        return redirect()->route('restaurants.search')->with('success', 'Restaurant et adresse créés avec succès !');
    }
    
    public function affichercommandes(Request $request, $id)
{
    // Récupérer les commandes pour un restaurant donné
    $commandes = DB::table('commande_repas as c')
        ->select(
            'c.id_commande_repas',
            'r.nom_etablissement as restaurant',
            'c.horaire_livraison',
            'c.temps_de_livraison',
            'c.id_chauffeur',
            'cha.nom_chauffeur',
            'r.id_restaurant',
            DB::raw("STRING_AGG(DISTINCT p.nom_produit, ', ') as produits"),
            DB::raw("STRING_AGG(DISTINCT pt.libelle_plat, ', ') as plats"),
            DB::raw("STRING_AGG(DISTINCT m.libelle_menu, ', ') as menus")
        )
        ->leftJoin('est_contenu_dans as ecd', 'ecd.id_commande_repas', '=', 'c.id_commande_repas')
        ->leftJoin('produit as p', 'ecd.id_produit', '=', 'p.id_produit')
        ->leftJoin('est_contenu_dans_menu as ecdm', 'ecdm.id_commande_repas', '=', 'c.id_commande_repas')
        ->leftJoin('menu as m', 'ecdm.id_menu', '=', 'm.id_menu')
        ->leftJoin('est_contenu_dans_plat as ecdp', 'ecdp.id_commande_repas', '=', 'c.id_commande_repas')
        ->leftJoin('plat as pt', 'ecdp.id_plat', '=', 'pt.id_plat')
        ->leftJoin('vends as v', 'p.id_produit', '=', 'v.id_produit')
        ->leftJoin('propose as pr', 'ecdp.id_plat', '=', 'pr.id_plat')
        ->leftJoin('propose_menu as pm', 'm.id_menu', '=', 'pm.id_menu')
        ->leftJoin('chauffeur as cha','cha.id_chauffeur','=','c.id_chauffeur')
        ->leftJoin('restaurant as r', function ($join) {
            $join->on('v.id_restaurant', '=', 'r.id_restaurant')
                ->orOn('pr.id_restaurant', '=', 'r.id_restaurant')
                ->orOn('pm.id_restaurant', '=', 'r.id_restaurant');
        })
        ->where('r.id_restaurant', '=', $id)
        ->groupBy('c.id_commande_repas', 'r.nom_etablissement','r.id_restaurant','cha.nom_chauffeur')
        ->get();

    // Récupérer les chauffeurs pour ce restaurant
    $livreurs = DB::table('restaurant as r')
        ->select('c.*')
        ->leftJoin('relie_a as ra', 'ra.id_restaurant', '=', 'r.id_restaurant')
        ->leftJoin('chauffeur as c', 'ra.id_chauffeur', '=', 'c.id_chauffeur')
        ->where('r.id_restaurant', $id)
        ->where('c.type_chauffeur', 'Livreur')
        ->get();

    // Si le formulaire a été soumis pour attribuer un chauffeur, mise à jour de la commande
    if ($request->has('id_chauffeur')) {
        $commande = CommandeRepas::findOrFail($request->id_commande_repas);
        $commande->id_chauffeur = $request->id_chauffeur;
        $commande->save();

        return redirect()->route('restaurants.affichercommandes', $id)
                         ->with('success', 'Chauffeur attribué avec succès.');
    }

    return view('restaurants.affichercommandes', compact('commandes', 'livreurs', 'id'));
}

    public function attribuerChauffeur(Request $request, $id)
    {
        $commande = CommandeRepas::findOrFail($request->id_commande_repas);

        // Si aucun chauffeur n'est attribué
        if ($request->id_chauffeur === 'null') {
            // Si un chauffeur était déjà attribué, on le remet disponible
            if ($commande->id_chauffeur !== null) {
                $chauffeur = Chauffeur::findOrFail($commande->id_chauffeur);
                $chauffeur->est_dispo = 'true'; // Remettre à disponible
                $chauffeur->save();
            }

            $commande->id_chauffeur = null; // Désattribution du chauffeur
        } else {
            // Si un chauffeur est sélectionné
            // Si un chauffeur était déjà attribué, on le remet disponible
            if ($commande->id_chauffeur !== null) {
                $chauffeur = Chauffeur::findOrFail($commande->id_chauffeur);
                $chauffeur->est_dispo = 'true'; // Remettre à disponible
                $chauffeur->save();
            }

            // Attribution du nouveau chauffeur
            $commande->id_chauffeur = $request->id_chauffeur;
            $chauffeur = Chauffeur::findOrFail($request->id_chauffeur);
            $chauffeur->est_dispo = 'false'; // Marquer comme non disponible
            $chauffeur->save();
        }

        // Sauvegarder la commande avec l'attribution du chauffeur
        $commande->save();

        return redirect()->route('restaurants.affichercommandes', $id)
                        ->with('success', 'Chauffeur attribué avec succès.');
    }




}




/* set search_path to s_uber;

SELECT 
    c.id_commande_repas,
    r.nom_etablissement AS restaurant,
    STRING_AGG(DISTINCT p.nom_produit, ', ') AS produits,
    STRING_AGG(DISTINCT pt.libelle_plat, ', ') AS plats,
    STRING_AGG(DISTINCT m.libelle_menu, ', ') AS menus
FROM
    commande_repas c
LEFT JOIN est_contenu_dans ecd ON ecd.id_commande_repas = c.id_commande_repas
LEFT JOIN produit p ON ecd.id_produit = p.id_produit
LEFT JOIN est_contenu_dans_menu ecdm ON ecdm.id_commande_repas = c.id_commande_repas
LEFT JOIN menu m ON ecdm.id_menu = m.id_menu
LEFT JOIN est_contenu_dans_plat ecdp ON ecdp.id_commande_repas = c.id_commande_repas
LEFT JOIN plat pt ON ecdp.id_plat = pt.id_plat
LEFT JOIN vends v ON p.id_produit = v.id_produit
LEFT JOIN propose pr ON ecdp.id_plat = pr.id_plat
LEFT JOIN propose_menu pm ON m.id_menu = pm.id_menu
LEFT JOIN restaurant r ON v.id_restaurant = r.id_restaurant
                        OR pr.id_restaurant = r.id_restaurant
                        OR pm.id_restaurant = r.id_restaurant
WHERE r.id_restaurant = 2
GROUP BY c.id_commande_repas, r.nom_etablissement;*/