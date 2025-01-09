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

    $rules = [
        'libelle_menu' => 'required|string|max:255',
        'prix_menu' => 'required|numeric|min:0',
        'photo_menu' => 'required|url',
        'plat_selection' => 'required|string|in:nouveau,existant',
    ];

    // Add conditional validation rules based on plat_selection
    if ($request->plat_selection === 'nouveau') {
        $rules += [
            'plat_nom' => 'required|string|max:255',
            'plat_prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categorie_produit,id_categorie_produit',
            'plat_photo' => 'required|url',
            'produit_nom' => 'required|string|max:255',
            'produit_prix' => 'required|numeric|min:0',
            'categorie_id2' => 'required|exists:categorie_produit,id_categorie_produit',
            'produit_photo' => 'required|url',
        ];
    } else {
        $rules += [
            'plat_existant' => 'required|exists:plat,id_plat',
            'produit_existant' => 'required|exists:produit,id_produit',
        ];
    }

    $validated = $request->validate($rules);
    


    
    // Création du menu
    $menu = Menu::create([
        'libelle_menu' => $validated['libelle_menu'],
        'prix_menu' => $validated['prix_menu'],
        'photo_menu' => $validated['photo_menu'] ?? null,
    ]);

    // Association au restaurant
    ProposeMenu::create([
        'id_menu' => $menu->id_menu,
        'id_restaurant' => $request->id_restaurant,
    ]);

    if ($validated['plat_selection'] === 'nouveau') {
        // Création du plat
        $plat = Plat::create([
            'libelle_plat' => $validated['plat_nom'],
            'prix_plat' => $validated['plat_prix'],
            'id_categorie_produit' => $validated['categorie_id'],
            'photo_plat' => $validated['plat_photo'] ?? null,
        ]);

        // Création du produit
        $produit = Produit::create([
            'nom_produit' => $validated['produit_nom'],
            'prix_produit' => $validated['produit_prix'],
            'photo_produit' => $validated['produit_photo'] ?? null,
            'id_categorie_produit' => $validated['categorie_id2'],

        ]);

        // Association avec le restaurant
        Vends::create([
            'id_restaurant' => $request->id_restaurant,
            'id_produit' => $produit->id_produit,
        ]);

        Propose::create([
            'id_restaurant' => $request->id_restaurant,
            'id_plat' => $plat->id_plat,
        ]);

        ComposeDe::create([
            'id_menu' => $menu->id_menu,
            'id_plat' => $plat->id_plat,
        ]);
    } else {
        // Plat et produit existants
        ComposeDe::create([
            'id_menu' => $menu->id_menu,
            'id_plat' => $validated['plat_existant'],
        ]);

        Compose::create([
            'id_menu' => $menu->id_menu,
            'id_produit' => $validated['produit_existant'],
        ]);
    }

    return redirect()
        ->action([RestaurantController::class, 'show'], ['id' => $request->id_restaurant])
        ->with('success', 'Menu créé avec succès');
}

}