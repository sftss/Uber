<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Propose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RestaurantController;


class PlatController extends Controller
{
    public function create($restaurant_id)
    {
        $categories = DB::table('categorie_produit')->get(); 

        return view('plat.create', [
            'restaurant_id' => $restaurant_id,
            'categories' => $categories 
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_restaurant' => 'required|integer|exists:restaurant,id_restaurant',
            'libelle_plat' => 'required|string|max:255',
            'prix_plat' => 'required|numeric',
            'photo_plat' => 'nullable|url|max:2048',
            'categorie_id' => 'nullable|exists:categorie_produit,id_categorie_produit',
        ]);


        $plat = new Plat();
        $plat->id_categorie_produit = $request->categorie_id;
        $plat->libelle_plat = $request->libelle_plat;
        $plat->prix_plat = $request->prix_plat;
        $plat->photo_plat = $request->photo_plat;
        $plat->save();

        
        $propose= new Propose();
        $propose->id_restaurant = $request->id_restaurant;
        $propose->id_plat = $plat->id_plat;

        $propose->save();

        return redirect()->action(
            [RestaurantController::class, 'show'],
            ['id' => $request->id_restaurant]
        )->with('successs', 'Produit ajouté avec succès');

    }


}
