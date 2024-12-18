<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Compose;
use App\Models\Plat;
use App\Models\Produit;
use App\Models\Vends;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RestaurantController;


class MenuController extends Controller
{
    public function create($restaurant_id)
    {

        $categories = DB::table('categorie_produit')->get(); // Pour autre filtre

        return view('menu.create', [
            'restaurant_id' => $restaurant_id,
            'categories' => $categories 
        ]);
    }

    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'id_restaurant' => 'required|integer|exists:restaurant,id_restaurant',
            'libelle_menu' => 'required|string|max:255',
            'prix_menu' => 'required|numeric',
            'photo_menu' => 'nullable|string|max:2048',
            'categorie_id' => 'nullable|exists:categorie_produit,id_categorie_produit',
            'plat1' => 'nullable|string|max:255',
            'prixplat1' => 'nullable|numeric',
            'photoplat1' => 'nullable|string|max:2048',
            // Ajoutez plus de validations pour d'autres plats si nécessaire
        ]);

        // Création du menu
        $menu = new Menu();
        $menu->id_restaurant = $request->id_restaurant;
        $menu->libelle_menu = $request->libelle_menu;
        $menu->prix_menu = $request->prix_menu;

        // Enregistrement de la photo du menu
        if ($request->filled('photo_menu')) {
            $menu->photo_menu = $request->photo_menu;
        }


        $menu->save();



        $produit=new Produit();
        $produit->nom_produit = $request->plat1;
        $produit->prix_produit = $request->prixplat1;
        $produit->id_categorie_produit = $request->categorie_id;
    
        if ($request->filled('photoplat1')) {
            $produit->photo_produit = $request->photoplat1;
        }

        $produit->save();
        
        $compose=new Compose();
        
        $compose->id_menu=$menu->id_menu;
        $compose->id_produit=$produit->id_produit;
        $compose->save();

        $vends=new Vends();
        $vends->id_restaurant= $menu->id_restaurant;
        $vends->id_produit= $produit->id_produit;
        $vends->save();

        return redirect()->action(
            [RestaurantController::class, 'show'],
            ['id' => $request->id_restaurant]
        )->with('successs', 'Produit ajouté avec succès');
    }
}
