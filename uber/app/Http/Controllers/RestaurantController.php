<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function filter(Request $request) {
        $recherche = $request->input('lieu');
        $livre = $request->has('livre');
        $emporter = $request->has('emporter');
        $categorie = $request->input('categorie');


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
            ->when($livre && !$emporter, function ($query) {
                return $query->where('restaurant.propose_livraison', 1)
                             ->where('restaurant.propose_retrait', 0);
            })
            ->when($emporter && !$livre, function ($query) {
                return $query->where('restaurant.propose_retrait', 1)
                             ->where('restaurant.propose_livraison', 0);
            })
            ->when($livre && $emporter, function ($query) {
                return $query->where('restaurant.propose_livraison', 1)
                             ->where('restaurant.propose_retrait', 1);
            })
            ->when($categorie, function ($query, $categorie) {
                return $query->where('categorie_restaurant.id_categorie', $categorie);
            })
            ->select('restaurant.*', 'adresse.ville', 'categorie_restaurant.lib_categorie')
            ->get();

        $categories = DB::table('categorie_restaurant')->get();

        return view('restaurants.filter', compact('restaurants', 'recherche', 'categories'));
    }
}
