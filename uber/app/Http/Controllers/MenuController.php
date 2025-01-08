<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Compose;
use App\Models\Plat;
use App\Models\Produit;
use App\Models\ComposeDe;
use App\Models\Vends;
use App\Models\ProposeMenu;
use App\Models\Propose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RestaurantController;


class MenuController extends Controller
{
    public function create($restaurant_id)
    {

        $categories = DB::table('categorie_produit')->get(); // Pour autre filtre

        $plat = DB::table('plat as pl')
        ->select('pl.*')
        ->leftJoin('propose as pr','pr.id_plat','=','pl.id_plat')
        ->where('pr.id_restaurant','=',$restaurant_id)
        ->get();

        $produit = DB::table('produit as pr')
        ->select('pr.*')
        ->leftJoin('vends as v','v.id_produit','=','pr.id_produit')
        ->where('v.id_restaurant','=',$restaurant_id)
        ->get();

        return view('menu.create', [
            'restaurant_id' => $restaurant_id,
            'categories' => $categories,
            'plats' => $plat,
            'produits' => $produit
        ]);
    }

    public function store(Request $request)
{
    // Validation de base
    $baseRules = [
        'libelle_menu' => 'required|string|max:255',
        'prix_menu' => 'required|numeric',
        'photo_menu' => 'required|url|max:2048',
        'plat_selection' => 'required|string|in:nouveau,existant',
    ];
    
    // Règles conditionnelles selon le type de sélection
    $rules = array_merge($baseRules, ($request->plat_selection === 'nouveau') ? [
        'plat_nom' => 'required|string|max:255',
        'plat_prix' => 'required|numeric',
        'categorie_id' => 'required|exists:categorie_produit,id_categorie_produit',
        'plat_photo' => 'required|url|max:2048',
        'produit_nom' => 'required|string|max:255',
        'produit_prix' => 'required|numeric',
        'categorie_id2' => 'required|exists:categorie_produit,id_categorie_produit',
        'produit_photo' => 'required|url|max:2048',
    ] : [
        'plat_existant' => 'required|exists:plat,id_plat',
        'produit_existant' => 'required|exists:produit,id_produit',
    ]);

    $validated = $request->validate($rules);

    // Créer le menu
    $menu = new Menu();
    $menu->libelle_menu = $request->libelle_menu;
    $menu->prix_menu = $request->prix_menu;
    
    if ($request->filled('photo_menu')) {
        $menu->photo_menu = $request->photo_menu;
    }
    
    $menu->save();
    
    // Ajouter l'association avec le restaurant
    $propose = new ProposeMenu();
    $propose->id_menu = $menu->id_menu;
    $propose->id_restaurant = $request->id_restaurant;
    $propose->save();



    if($request->plat_selection == 'nouveau'){

        
        // Créer le plat
        $plat = new Plat();
        $plat->libelle_plat = $request->plat_nom;
        $plat->prix_plat = $request->plat_prix;
        $plat->id_categorie_produit = $request->categorie_id;
        if ($request->plat_photo) {
            $plat->photo_plat = $request->plat_photo;;
        }
        $plat->save();
        
        $produit = new Produit();
        $produit->nom_plat = $request->produit_nom;
        $produit->prix_produit = $request->produit_prix;
        $produit->id_categorie_produit = $request->categorie_id2;
        if ($request->produit_photo) {
            $produit->photo_produit = $request->produit_photo;;
        }
        $produit->save();
        
        $vends = new Vends();
        $vends->id_restaurant = $request->id_restaurant;
        $vends->id_produit = $plat->id_produit;
        $vends->save();
        

        $propose = new Propose();
        $propose -> id_restaurant = $request->id_restaurant;
        $propose -> id_plat = $plat->id_plat;
        $propose->save();

    }

    // Gérer le cas où l'utilisateur a sélectionné un plat existant
    if ($request->plat_selection == 'existant') {

        $platExistant = Plat::find($request->plat_existant);
        if ($platExistant) {
            // Lier le plat au menu via la table "Compose"
            $estcompose = new ComposeDe();
            $estcompose->id_menu = $menu->id_menu;
            $estcompose->id_plat = $platExistant->id_plat;
            $estcompose->save();

        }

        $produitExistant = Produit::find($request->produit_existant);
        if ($produitExistant) {
            $compose = new Compose();
            $compose->id_menu = $menu->id_menu;
            $compose->id_produit = $produitExistant->id_produit;
            $compose->save();
        }


    }

    // Redirection avec succès
    return redirect()->action([RestaurantController::class, 'show'],['id' => $request->id_restaurant])->with('successs', 'Menu créé avec succès');
}
}