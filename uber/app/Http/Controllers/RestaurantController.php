<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Adresse;
use App\Models\Restaurant;
use App\Models\APourCategorie;
use Illuminate\Support\Facades\Auth;


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
                    if ($livre) $q->where('restaurant.propose_livraison', 1);
                    if ($emporter) $q->where('restaurant.propose_retrait', 1);
                });
            })
            ->distinct()
            ->paginate(10);

            $categories = DB::table('categorie_restaurant')->get();

            return view('restaurants.filter', compact('restaurants', 'recherche', 'categories', 'horaireOuverture', 'horaireFermeture'));
    }

    public function show($id, Request $request)
    {
        $restaurant = DB::table('restaurant')
            ->join('adresse', 'restaurant.id_adresse', '=', 'adresse.id_adresse')
            ->leftJoin('a_pour_categorie', 'restaurant.id_restaurant', '=', 'a_pour_categorie.id_restaurant')
            ->leftJoin('categorie_restaurant', 'a_pour_categorie.id_categorie', '=', 'categorie_restaurant.id_categorie')
            ->select('restaurant.*', 'adresse.ville', 'adresse.rue', 'adresse.cp', 'categorie_restaurant.lib_categorie','id_proprietaire')
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
dd($restaurant);
        $categories = DB::table('categorie_produit')->get(); // Pour autre filtre
        $categorieId = $request->input('categorie', ''); // Catégorie sélectionnée
        $categoriesProduits = DB::table('categorie_produit')->get();

        return view('restaurants.show', compact('restaurant', 'menus', 'plats', 'produits', 'categoriesProduits', 'categorieId'));
    }

    public function affichercreation()
    {
        $categories = DB::table('categorie_restaurant')->get(); // Pour autre filtre
        return view('restaurants.create', compact('categories'));
    }

    // Traiter les données soumises par le formulaire
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            // Validation des données
            $validatedData = $request->validate([
                'rue' => 'required|string|max:255',
                'cp' => 'required|digits:5',
                'ville' => 'required|string|max:100',
                'nom_etablissement' => 'required|string|max:255',
                'description_etablissement' => 'nullable|string',
                'propose_livraison' => 'boolean',
                'propose_retrait' => 'boolean',
                'category' => 'required|exists:categorie_restaurant,id_categorie',
                'horaires_ouverture' => 'required|date_format:H:i',
                'horaires_fermeture' => 'required|date_format:H:i',
                'photo_restaurant' => 'required|string|max:500',
            ]);

            // Création de l'adresse
            $departement = substr($validatedData['cp'], 0, 2) + 1;

            $adresse = Adresse::create([
                'id_departement' => $departement,
                'rue' => $validatedData['rue'],
                'ville' => $validatedData['ville'],
                'cp' => $validatedData['cp'],
            ]);

            // Création du restaurant
            $restaurant = Restaurant::create([
                'nom_etablissement' => $validatedData['nom_etablissement'],
                'description_etablissement' => $validatedData['description_etablissement'],
                'propose_livraison' => $request->has('propose_livraison') ? 1 : 0,
                'propose_retrait' => $request->has('propose_retrait') ? 1 : 0,
                'horaires_ouverture' => $validatedData['horaires_ouverture'],
                'horaires_fermeture' => $validatedData['horaires_fermeture'],
                'id_adresse' => $adresse->id_adresse,
                'photo_restaurant' => $validatedData['photo_restaurant'],
                'id_proprietaire' => Auth::user()->id_client,
            ]);
            // Association du restaurant à la catégorie
            DB::table('a_pour_categorie')->insert([
                'id_restaurant' => $restaurant->id_restaurant,
                'id_categorie' => $validatedData['category'],
            ]);
        });

        return redirect()->route('affichercreation')->with('success', 'Restaurant et adresse créés avec succès !');
    }
    public function ajouterproduit(){

    }
    public function ajouterplat(){
        
    }
    public function ajoutermenu(){
        
    }

}
